<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRContextInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRObligation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\ObligationCode;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Validates FHIR resources against their defined constraints and profiles.
 *
 * Combines Symfony validation with FHIR-specific checks: extension contexts, modifier extension
 * resolution, must-support fields, FHIRPath invariants, and obligation codes. Returns a structured
 * report of violations at different severity levels (error, warning, info).
 */
final class FHIRValidationService implements FHIRValidationServiceInterface
{
    private const CONTEXT_PERMIT = 1;

    private const CONTEXT_DEFER  = 0;

    private const CONTEXT_DENY   = -1;

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly FHIRPathService $pathService,
        private readonly ?FHIRIGTypeRegistry $registry = null,
        private readonly FHIRTypeHierarchyResolverInterface $typeResolver = new FhirPropertyTypeHierarchyResolver(),
        private readonly FHIRValidationReportMapper $reportMapper = new FHIRValidationReportMapper(),
    ) {
    }

    /**
     * Validates a FHIR resource against constraints, extension contexts, and optional profiles.
     *
     * @param object                     $resource               The FHIR resource to validate
     * @param list<string>               $profileUrls            FHIR profile canonical URLs to validate against (empty array uses default constraints only)
     * @param bool                       $includeMustSupportInfo When true, includes info-level violations for unpopulated must-support properties
     * @param FHIRObligationContext|null $obligationContext      When provided, applies obligation codes (SHALL_POPULATE, SHOULD_POPULATE, etc.)
     *
     * @return FHIRValidationReport A structured report containing all violations found at all severity levels
     */
    public function validate(
        object $resource,
        array $profileUrls = [],
        bool $includeMustSupportInfo = false,
        ?FHIRObligationContext $obligationContext = null,
    ): FHIRValidationReport {
        $groups = ['Default', ...$profileUrls];

        $rawViolations = $this->validator->validate($resource, null, $groups);

        $violations = [];
        foreach ($rawViolations as $rawViolation) {
            $violations[] = $this->mapViolation($rawViolation);
        }

        if ($includeMustSupportInfo) {
            foreach ($this->collectMustSupportInfo($resource) as $infoViolation) {
                $violations[] = $infoViolation;
            }
        }

        $contextVisited = [];
        foreach ($this->validateExtensionContexts($resource, $this->getResourceFhirType($resource), '', [], $contextVisited, []) as $contextViolation) {
            $violations[] = $contextViolation;
        }

        if ($this->registry !== null) {
            $visited = [];
            foreach ($this->validateModifierExtensions($resource, '', $visited) as $modifierViolation) {
                $violations[] = $modifierViolation;
            }
        }

        if ($obligationContext !== null) {
            foreach ($this->collectObligationViolations($resource, $obligationContext) as $obligationViolation) {
                $violations[] = $obligationViolation;
            }
            $violations = $this->applyNoErrorSuppression($resource, $violations, $obligationContext);
        }

        return new FHIRValidationReport($violations);
    }

    public function validateForOperation(
        object $resource,
        string $mode = '',
        array $profileUrls = [],
        string $fhirVersion = 'R4',
    ): object {
        if (!in_array($mode, ['', 'create', 'update', 'profile', 'delete'], true)) {
            throw new \InvalidArgumentException(sprintf('Unsupported mode "%s". Supported values: \'\', create, update, profile, delete.', $mode));
        }

        if ($mode === 'delete') {
            $report = new FHIRValidationReport([
                new FHIRValidationViolation(
                    severity: 'info',
                    path: '',
                    message: 'delete mode: referential integrity check requires a server context — library cannot perform this validation.',
                    constraintClass: self::class,
                    profileGroup: null,
                    invariantKey: null,
                    code: FHIRViolationCode::INFO,
                ),
            ]);

            return $this->reportMapper->toOperationOutcome($report, $fhirVersion);
        }

        $report = $this->validate($resource, $profileUrls);

        return $this->reportMapper->toOperationOutcome($report, $fhirVersion);
    }

    /**
     * Converts a Symfony ConstraintViolation into a structured FHIRValidationViolation.
     *
     * Maps severity codes, extracts the constraint type and profile group, and preserves violation
     * path and message. Handles FHIRPath invariants and FHIRProfile constraints specially.
     *
     * @param ConstraintViolationInterface $violation The Symfony constraint violation to convert
     *
     * @return FHIRValidationViolation A structured FHIR validation violation
     */
    private function mapViolation(ConstraintViolationInterface $violation): FHIRValidationViolation
    {
        $code = $violation->getCode();

        $severity = match ($code) {
            FHIRViolationCode::WARNING           => 'warning',
            FHIRViolationCode::INFO              => 'info',
            FHIRViolationCode::EVAL_ERROR        => 'info',
            FHIRViolationCode::UNCHECKED_BINDING => 'info',
            default                              => 'error',
        };

        $constraint      = $violation->getConstraint();
        $constraintClass = $constraint !== null ? $constraint::class : '';

        $profileGroup = null;
        if ($constraint instanceof FHIRProfileConstraint
            && $constraint->groups !== null
            && $constraint->groups !== []) {
            $profileGroup = $constraint->groups[0];
        }

        $invariantKey = null;
        if ($constraint instanceof FHIRPathInvariant) {
            $invariantKey = $constraint->key;
        }

        return new FHIRValidationViolation(
            severity: $severity,
            path: (string) $violation->getPropertyPath(),
            message: (string) $violation->getMessage(),
            constraintClass: $constraintClass,
            profileGroup: $profileGroup,
            invariantKey: $invariantKey,
            parameters: $violation->getParameters(),
            code: $code,
        );
    }

    /**
     * Collects info-level violations for unpopulated must-support properties.
     *
     * Reflects over the resource's properties, finds those with #[FHIRMustSupport] attributes,
     * and generates info-level violations for properties that are empty or not set.
     *
     * @param object $resource The FHIR resource to inspect
     *
     * @return list<FHIRValidationViolation> Info-level violations for empty must-support properties
     */
    private function collectMustSupportInfo(object $resource): array
    {
        $violations = [];
        $ref        = new \ReflectionClass($resource);

        foreach ($ref->getProperties() as $property) {
            if ($property->getAttributes(FHIRMustSupport::class) === []) {
                continue;
            }

            // Deserializers bypass the constructor, so an absent field is uninitialized (not null).
            // Treat uninitialized as not-populated so the must-support info still fires (and we never
            // call getValue() on an uninitialized typed property, which would throw \Error).
            $value = $property->isInitialized($resource) ? $property->getValue($resource) : null;

            if ($value !== null && $value !== []) {
                continue;
            }

            $violations[] = new FHIRValidationViolation(
                severity: 'info',
                path: $property->getName(),
                message: sprintf('Must-support property "%s" is not populated.', $property->getName()),
                constraintClass: FHIRMustSupport::class,
                profileGroup: null,
                invariantKey: null,
            );
        }

        return $violations;
    }

    /**
     * Pass 2: check extension context and contextInvariant constraints for all extensions
     * throughout the resource tree. Walks nested sub-elements recursively.
     *
     * Extension contexts are matched against the current element using a type resolver:
     * bare type-name contexts (e.g. "HumanName", "DomainResource") match the element's resolved
     * supertype set, and foreign-root dotted paths (e.g. "ElementDefinition.binding" inside
     * StructureDefinition) match type-rooted path candidates. Without a resolver these defer.
     *
     * @param list<string>      $typeCandidates        type-rooted dotted addresses for the current element
     * @param array<int, true>  $visited               spl_object_id keys of already-visited objects (cycle guard)
     * @param list<string>|null $ancestorExtensionUrls canonical URLs of the enclosing extension chain, outermost
     *                                                 first; [] at element level (definitively not nested), null
     *                                                 when an enclosing URL is unreadable (chain unknown → defer)
     *
     * @return list<FHIRValidationViolation>
     */
    private function validateExtensionContexts(object $resource, string $fhirPath, string $relPath, array $typeCandidates, array &$visited, ?array $ancestorExtensionUrls): array
    {
        $id = spl_object_id($resource);

        if (isset($visited[$id])) {
            return [];
        }

        $visited[$id] = true;
        $violations   = [];

        if (method_exists($resource, 'getExtensions')) {
            $extViolationPath = $relPath !== '' ? $relPath . '.extension' : 'extension';

            // Resource root is the first segment of the resource-rooted path. Same-root dotted
            // contexts are matched purely on the path string (no type info needed); foreign-root
            // dotted contexts are matched against the type-rooted candidates; bare-type contexts
            // are matched against this element's resolved supertype set.
            $dotPos        = strpos($fhirPath, '.');
            $resourceRoot  = $dotPos !== false ? substr($fhirPath, 0, $dotPos) : $fhirPath;
            $ownSupertypes = $this->typeResolver->resolveTypeHierarchy($resource);

            // At the root element the FHIR type is the resource type, always known without a
            // resolver. Seed it so bare-type matching against the resource type works (and a
            // non-matching bare context is denied) even with NullFHIRTypeHierarchyResolver,
            // preserving pre-resolver behaviour. A real resolver adds the supertype chain.
            if ($dotPos === false && !in_array($fhirPath, $ownSupertypes, true)) {
                $ownSupertypes[] = $fhirPath;
            }

            /** @var list<object> $extensions */
            $extensions = $resource->getExtensions();

            foreach ($extensions as $extIndex => $extension) {
                $ref          = new \ReflectionClass($extension);
                $contextAttrs = array_map(
                    static fn (\ReflectionAttribute $a): FHIRExtensionContext => $a->newInstance(),
                    $ref->getAttributes(FHIRExtensionContext::class),
                );

                if ($contextAttrs !== []
                    && $this->classifyExtensionContexts($contextAttrs, $resource, $fhirPath, $resourceRoot, $typeCandidates, $ownSupertypes, $ancestorExtensionUrls) === self::CONTEXT_DENY
                ) {
                    $url          = $this->readExtensionUrl($extension);
                    $violations[] = new FHIRValidationViolation(
                        severity: 'error',
                        path: $extViolationPath,
                        message: sprintf(
                            'Extension "%s" is not permitted on element "%s".',
                            $url,
                            $fhirPath,
                        ),
                        constraintClass: FHIRExtensionContext::class,
                        profileGroup: null,
                        invariantKey: null,
                    );
                }

                // Context invariants are authored against the extension's declared element
                // contexts; evaluating them against a parent Extension (nested frame) would
                // yield empty results and false errors. Defer them for nested extensions —
                // same defer-not-deny property as context classification.
                $invariantAttrs = $resource instanceof FHIRExtensionInterface ? [] : array_map(
                    static fn (\ReflectionAttribute $a): FHIRContextInvariant => $a->newInstance(),
                    $ref->getAttributes(FHIRContextInvariant::class),
                );

                foreach ($invariantAttrs as $invariant) {
                    try {
                        $result = $this->pathService->evaluate($invariant->expression, $resource);
                    } catch (FHIRPathException) {
                        // Engine limitation, not non-conformance: surface as INFO eval-error.
                        // Only FHIRPath engine exceptions are downgraded here; any other throwable
                        // (a genuine bug) propagates rather than being masked as an info result.
                        $url          = $this->readExtensionUrl($extension);
                        $violations[] = new FHIRValidationViolation(
                            severity: 'info',
                            path: $extViolationPath,
                            message: sprintf(
                                'Extension "%s" contextInvariant could not be evaluated: %s',
                                $url,
                                $invariant->expression,
                            ),
                            constraintClass: FHIRContextInvariant::class,
                            profileGroup: null,
                            invariantKey: null,
                            code: FHIRViolationCode::EVAL_ERROR,
                        );

                        continue;
                    }

                    $passed = $result->count() === 1 && $result->first() === true;

                    if (!$passed) {
                        $url          = $this->readExtensionUrl($extension);
                        $violations[] = new FHIRValidationViolation(
                            severity: 'error',
                            path: $extViolationPath,
                            message: sprintf(
                                'Extension "%s" contextInvariant failed: %s',
                                $url,
                                $invariant->expression,
                            ),
                            constraintClass: FHIRContextInvariant::class,
                            profileGroup: null,
                            invariantKey: null,
                        );
                    }
                }

                // Narrow descent into this extension's own sub-extensions, growing the
                // ancestor-URL chain so nested type=extension contexts can be classified.
                // If this extension's URL is unreadable the nested chain becomes unknown
                // (null) — nested type=extension contexts then defer rather than deny.
                $nestedChain = null;
                if ($ancestorExtensionUrls !== null) {
                    $extUrl = $this->readExtensionUrl($extension);
                    if ($extUrl !== '') {
                        $nestedChain = [...$ancestorExtensionUrls, $extUrl];
                    }
                }

                foreach ($this->validateExtensionContexts($extension, $fhirPath, $extViolationPath . '[' . $extIndex . ']', $typeCandidates, $visited, $nestedChain) as $v) {
                    $violations[] = $v;
                }
            }
        }

        if ($resource instanceof FHIRExtensionInterface) {
            // Extension frames classify only their sub-extension chain (handled above); the
            // extension's other properties (value, url) are not walked. Extensions borne by an
            // extension's *value* element therefore remain unvisited — deferred M11 scope.
            return $violations;
        }

        $ref = new \ReflectionClass($resource);

        foreach ($ref->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            if (in_array($prop->getName(), ['extension', 'modifierExtension'], true)) {
                continue;
            }
            if ($prop->isInitialized($resource) === false) {
                continue;
            }

            $value       = $prop->getValue($resource);
            $subFhirPath = $fhirPath . '.' . $prop->getName();
            $subRelPath  = $relPath !== '' ? $relPath . '.' . $prop->getName() : $prop->getName();

            if (is_object($value)) {
                $childCandidates = $this->childTypeCandidates($value, $resource, $prop->getName(), $typeCandidates);
                foreach ($this->validateExtensionContexts($value, $subFhirPath, $subRelPath, $childCandidates, $visited, []) as $v) {
                    $violations[] = $v;
                }
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        $childCandidates = $this->childTypeCandidates($item, $resource, $prop->getName(), $typeCandidates);
                        foreach ($this->validateExtensionContexts($item, $subFhirPath, $subRelPath . '[' . $i . ']', $childCandidates, $visited, []) as $v) {
                            $violations[] = $v;
                        }
                    }
                }
            }
        }

        return $violations;
    }

    /**
     * Aggregate an extension's contexts into a single verdict.
     *
     * Permitted if ANY context permits; otherwise deferred if ANY context could not be
     * confidently evaluated; otherwise denied (all contexts were evaluable and none matched).
     *
     * @param list<FHIRExtensionContext> $contexts
     * @param object                     $bearingElement        the element the extension appears on
     * @param list<string>               $typeCandidates
     * @param list<string>               $ownSupertypes
     * @param list<string>|null          $ancestorExtensionUrls enclosing extension-URL chain; null when unknown
     *
     * @return self::CONTEXT_*
     */
    private function classifyExtensionContexts(array $contexts, object $bearingElement, string $fhirPath, string $resourceRoot, array $typeCandidates, array $ownSupertypes, ?array $ancestorExtensionUrls): int
    {
        $anyDeferred = false;

        foreach ($contexts as $ctx) {
            $verdict = $this->classifyContext($ctx, $bearingElement, $fhirPath, $resourceRoot, $typeCandidates, $ownSupertypes, $ancestorExtensionUrls);

            if ($verdict === self::CONTEXT_PERMIT) {
                return self::CONTEXT_PERMIT;
            }
            if ($verdict === self::CONTEXT_DEFER) {
                $anyDeferred = true;
            }
        }

        return $anyDeferred ? self::CONTEXT_DEFER : self::CONTEXT_DENY;
    }

    /**
     * Classify a single extension context against the current element.
     *
     * Type resolution is monotonic: it may only turn a would-be denial into a PERMIT, never
     * introduce a new denial. Denials therefore arise only from information that needs no type
     * inference — the resource type at the root, same-resource-root path strings, a fhirpath
     * expression that confidently evaluates to boolean false against the bearing element, and a
     * fully-known ancestor-extension chain that excludes a type=extension context's declared
     * parent URL. Bare and foreign-root contexts that do not match are deferred (never denied),
     * so wiring a resolver cannot create a false positive; it can only clear one. Element
     * contexts on extension-borne nested extensions always defer on mismatch.
     *
     * @param list<string>      $typeCandidates        type-rooted dotted addresses for this element
     * @param list<string>      $ownSupertypes         FHIR type name of this element plus its supertypes
     * @param list<string>|null $ancestorExtensionUrls enclosing extension-URL chain, [] when the bearing
     *                                                 element is definitively not nested in an extension,
     *                                                 null when the chain could not be fully assembled
     *
     * @return self::CONTEXT_*
     */
    private function classifyContext(FHIRExtensionContext $ctx, object $bearingElement, string $fhirPath, string $resourceRoot, array $typeCandidates, array $ownSupertypes, ?array $ancestorExtensionUrls): int
    {
        if ($ctx->type === 'fhirpath') {
            return $this->classifyFhirpathContext($ctx, $bearingElement);
        }

        if ($ctx->type === 'extension') {
            // The extension is only permitted nested inside an extension carrying the declared
            // canonical URL. Deny only on a FULLY KNOWN chain that excludes it — including the
            // definitive empty chain of an extension borne directly on an element. An unknown
            // or partially assembled chain defers (defer-not-deny).
            if ($ancestorExtensionUrls === null) {
                return self::CONTEXT_DEFER;
            }

            return in_array($ctx->expression, $ancestorExtensionUrls, true)
                ? self::CONTEXT_PERMIT
                : self::CONTEXT_DENY;
        }

        if ($ctx->type !== 'element') {
            return self::CONTEXT_PERMIT; // unknown context types are permitted (pre-M11 behaviour)
        }

        // Element contexts address element paths, which a nested extension does not sit on.
        // For an extension borne by another extension, a non-matching element context defers
        // rather than denies: nested element-path semantics are out of scope (M11). At nested
        // sites confident denials come only from the type=extension arm above or from a
        // fhirpath expression evaluating to a single false against the parent extension —
        // legitimate, since the parent extension IS the bearing element there.
        $bearingIsExtension = $bearingElement instanceof FHIRExtensionInterface;

        $expr   = $ctx->expression;
        $isRoot = !str_contains($fhirPath, '.');

        if (!str_contains($expr, '.')) {
            // Bare type name: match against this element's resolved supertype set.
            if (in_array($expr, $ownSupertypes, true)) {
                return self::CONTEXT_PERMIT;
            }

            // At the resource root the type is definitively known (resolver-independent), so a
            // non-matching bare context is a confident denial. At nested elements the type may be
            // abstract or unresolvable, so defer rather than risk a false positive.
            return ($isRoot && !$bearingIsExtension) ? self::CONTEXT_DENY : self::CONTEXT_DEFER;
        }

        $exprRoot = substr($expr, 0, (int) strpos($expr, '.'));

        if ($exprRoot === $resourceRoot) {
            // Same resource root: a pure string comparison on the resource-rooted path,
            // definitive regardless of type resolution.
            if ($fhirPath === $expr || str_starts_with($fhirPath, $expr . '.')) {
                return self::CONTEXT_PERMIT;
            }

            return $bearingIsExtension ? self::CONTEXT_DEFER : self::CONTEXT_DENY;
        }

        // Foreign root (e.g. "ElementDefinition.binding" inside StructureDefinition): permitted
        // when a type-rooted candidate matches (a path ancestor is typed with the context root).
        // A non-match is deferred, never denied — that would require complete type resolution of
        // every path segment, which we do not guarantee.
        foreach ($typeCandidates as $candidate) {
            if ($candidate === $expr || str_starts_with($candidate, $expr . '.')) {
                return self::CONTEXT_PERMIT;
            }
        }

        return self::CONTEXT_DEFER;
    }

    /**
     * Classify a fhirpath context by evaluating its expression against the bearing element.
     *
     * Denial requires confident evaluation: only a single boolean `false` denies. An empty
     * result is indistinguishable from an engine resolution gap (the engine returns empty even
     * for a type-matching `ofType(...)`), and a FHIRPathException is an engine limitation, not
     * non-conformance — both defer, preserving the defer-not-deny safety property. Only
     * FHIRPath engine exceptions are downgraded; any other throwable (a genuine bug)
     * propagates rather than being masked as a deferral.
     *
     * @return self::CONTEXT_*
     */
    private function classifyFhirpathContext(FHIRExtensionContext $ctx, object $bearingElement): int
    {
        try {
            $result = $this->pathService->evaluate($ctx->expression, $bearingElement);
        } catch (FHIRPathException) {
            return self::CONTEXT_DEFER;
        }

        if ($result->count() === 0) {
            return self::CONTEXT_DEFER;
        }

        if ($result->count() === 1 && $result->first() === false) {
            return self::CONTEXT_DENY;
        }

        return self::CONTEXT_PERMIT;
    }

    /**
     * Read an extension's canonical URL, tolerating partially-constructed objects.
     *
     * Deserializers may instantiate Extension without running its constructor, leaving the
     * promoted typed $url property uninitialized — getExtensionUrl() then throws an Error
     * instead of returning null. Treat that exactly like an absent URL: the ancestor chain
     * becomes unknown and dependent classifications defer (defer-not-deny).
     */
    private function readExtensionUrl(object $extension): string
    {
        if (!method_exists($extension, 'getExtensionUrl')) {
            return '';
        }

        try {
            return $extension->getExtensionUrl() ?? '';
        } catch (\Error) {
            // Uninitialized typed property on a constructor-bypassed object -- rationale:
            // unreadable URL must defer, not crash validation of the whole resource.
            return '';
        }
    }

    /**
     * Compute the type-rooted path candidates for a child element.
     *
     * The child's candidates are the parent's candidates each extended by the property name,
     * plus a fresh candidate rooted at the child's own concrete FHIR type (so a dotted context
     * such as "HumanName.family" can match the family element under Patient.name). The concrete
     * type is the instance type when resolvable, falling back to the declared property type.
     *
     * @param list<string> $parentCandidates
     *
     * @return list<string>
     */
    private function childTypeCandidates(object $child, object $parent, string $propertyName, array $parentCandidates): array
    {
        $hierarchy = $this->typeResolver->resolveTypeHierarchy($child);
        $concrete  = $hierarchy[0] ?? $this->typeResolver->resolvePropertyType($parent, $propertyName);

        $candidates = [];
        foreach ($parentCandidates as $candidate) {
            $candidates[] = $candidate . '.' . $propertyName;
        }
        if ($concrete !== null) {
            $candidates[] = $concrete;
        }

        return $candidates;
    }

    /**
     * Resolves the FHIR type name of a resource object.
     *
     * Reads the #[FhirResource] attribute if present, falling back to the class name with
     * "Resource" suffix removed (e.g., "PatientResource" becomes "Patient").
     *
     * @param object $resource The FHIR resource object to inspect
     *
     * @return string The FHIR type name (e.g., "Patient", "Observation", "Bundle")
     */
    private function getResourceFhirType(object $resource): string
    {
        $ref   = new \ReflectionClass($resource);
        $attrs = $ref->getAttributes(FhirResource::class);

        if ($attrs !== []) {
            /** @var FhirResource $attr */
            $attr = $attrs[0]->newInstance();

            return $attr->type;
        }

        $name = $ref->getShortName();

        return str_ends_with($name, 'Resource') ? substr($name, 0, -8) : $name;
    }

    /**
     * Walk $resource and all nested complex-type objects, raising fhir:error for each
     * modifier extension whose URL is not resolvable via the IG type registry.
     *
     * Non-modifier (regular) extensions with unknown URLs produce no violation per the FHIR spec
     * ("Systems SHOULD ignore extensions they do not recognise"). Only modifier extensions are
     * enforced, because they change the meaning of the containing resource in an unknown way.
     *
     * @param array<int, true> $visited spl_object_id keys of already-visited objects (cycle guard)
     *
     * @return list<FHIRValidationViolation>
     */
    private function validateModifierExtensions(object $resource, string $path, array &$visited): array
    {
        $id = spl_object_id($resource);

        if (isset($visited[$id])) {
            return [];
        }

        $visited[$id] = true;
        $violations   = [];

        if (property_exists($resource, 'modifierExtension') && isset($resource->modifierExtension) && is_array($resource->modifierExtension)) {
            $extPath = $path !== '' ? $path . '.modifierExtension' : 'modifierExtension';

            foreach ($resource->modifierExtension as $ext) {
                if (!is_object($ext)) {
                    continue;
                }

                $url = method_exists($ext, 'getExtensionUrl') ? $ext->getExtensionUrl() : null;

                if ($url === null) {
                    continue;
                }

                /** @var FHIRIGTypeRegistry $registry */
                $registry = $this->registry;

                if ($registry->resolveExtensionClass($url) === null) {
                    $violations[] = new FHIRValidationViolation(
                        severity: 'error',
                        path: $extPath,
                        message: sprintf('Unknown modifier extension: %s — resource cannot be safely processed', $url),
                        constraintClass: FHIRIGTypeRegistry::class,
                        profileGroup: null,
                        invariantKey: null,
                    );
                }
            }
        }

        $ref = new \ReflectionClass($resource);

        foreach ($ref->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            if ($prop->getName() === 'modifierExtension') {
                continue;
            }
            if ($prop->isInitialized($resource) === false) {
                continue;
            }

            $value    = $prop->getValue($resource);
            $propPath = $path !== '' ? $path . '.' . $prop->getName() : $prop->getName();

            if (is_object($value)) {
                foreach ($this->validateModifierExtensions($value, $propPath, $visited) as $v) {
                    $violations[] = $v;
                }
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        foreach ($this->validateModifierExtensions($item, $propPath . '[' . $i . ']', $visited) as $v) {
                            $violations[] = $v;
                        }
                    }
                }
            }
        }

        return $violations;
    }

    /**
     * Collects violations for unmet obligation requirements (SHALL_POPULATE, SHOULD_POPULATE, etc.).
     *
     * Reflects over the resource's properties, finds those with #[FHIRObligation] attributes matching
     * the provided context, and generates violations when required fields are empty.
     *
     * @param object                $resource The FHIR resource to inspect
     * @param FHIRObligationContext $context  The obligation context to match against
     *
     * @return list<FHIRValidationViolation> Violations for empty or missing obligated properties
     */
    private function collectObligationViolations(object $resource, FHIRObligationContext $context): array
    {
        $violations = [];
        $ref        = new \ReflectionClass($resource);

        foreach ($ref->getProperties() as $property) {
            $attrs = $property->getAttributes(FHIRObligation::class);

            if ($attrs === []) {
                continue;
            }

            // Uninitialized (constructor-bypassed deserialization) counts as absent → empty, so the
            // obligation still fires; never call getValue() on an uninitialized typed property (\Error).
            $value   = $property->isInitialized($resource) ? $property->getValue($resource) : null;
            $isEmpty = $value === null || $value === [];

            foreach ($attrs as $attr) {
                /** @var FHIRObligation $obligation */
                $obligation = $attr->newInstance();

                if (!$context->matchesObligation($obligation)) {
                    continue;
                }

                if ($obligation->filter !== null) {
                    continue; // FHIRPath filter evaluation deferred to backlog
                }

                $code = ObligationCode::tryFrom($obligation->code);

                if ($code === null || !$isEmpty) {
                    continue;
                }

                $severity = match ($code) {
                    ObligationCode::SHALL_POPULATE          => 'error',
                    ObligationCode::SHOULD_POPULATE         => 'warning',
                    ObligationCode::SHALL_POPULATE_IF_KNOWN => 'info',
                    default                                 => null,
                };

                if ($severity === null) {
                    continue;
                }

                $violations[] = new FHIRValidationViolation(
                    severity: $severity,
                    path: $property->getName(),
                    message: sprintf('Obligation %s: property "%s" must be populated.', $obligation->code, $property->getName()),
                    constraintClass: FHIRObligation::class,
                    profileGroup: null,
                    invariantKey: null,
                );
            }
        }

        return $violations;
    }

    /**
     * Removes fhir:error violations for properties where a matching SHALL:no-error obligation applies.
     *
     * @param list<FHIRValidationViolation> $violations
     *
     * @return list<FHIRValidationViolation>
     */
    private function applyNoErrorSuppression(object $resource, array $violations, FHIRObligationContext $context): array
    {
        $suppressedPaths = [];
        $ref             = new \ReflectionClass($resource);

        foreach ($ref->getProperties() as $property) {
            foreach ($property->getAttributes(FHIRObligation::class) as $attr) {
                /** @var FHIRObligation $obligation */
                $obligation = $attr->newInstance();

                if ($context->matchesObligation($obligation)
                    && ObligationCode::tryFrom($obligation->code) === ObligationCode::SHALL_NO_ERROR
                ) {
                    $suppressedPaths[] = $property->getName();
                }
            }
        }

        if ($suppressedPaths === []) {
            return $violations;
        }

        return array_values(array_filter(
            $violations,
            static fn (FHIRValidationViolation $v): bool => !(
                $v->severity === 'error' && in_array($v->path, $suppressedPaths, true)
            ),
        ));
    }
}
