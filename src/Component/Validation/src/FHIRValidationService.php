<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRContextInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRObligation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
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
    ) {
    }

    /**
     * Validates a FHIR resource against constraints, extension contexts, and optional profiles.
     *
     * @param object $resource The FHIR resource to validate
     * @param array $profileUrls FHIR profile canonical URLs to validate against (empty array uses default constraints only)
     * @param bool $includeMustSupportInfo When true, includes info-level violations for unpopulated must-support properties
     * @param FHIRObligationContext|null $obligationContext When provided, applies obligation codes (SHALL_POPULATE, SHOULD_POPULATE, etc.)
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
        foreach ($this->validateExtensionContexts($resource, $this->getResourceFhirType($resource), '', [], $contextVisited) as $contextViolation) {
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

    /**
     * Converts a Symfony ConstraintViolation into a structured FHIRValidationViolation.
     *
     * Maps severity codes, extracts the constraint type and profile group, and preserves violation
     * path and message. Handles FHIRPath invariants and FHIRProfile constraints specially.
     *
     * @param ConstraintViolationInterface $violation The Symfony constraint violation to convert
     * @return FHIRValidationViolation A structured FHIR validation violation
     */
    private function mapViolation(ConstraintViolationInterface $violation): FHIRValidationViolation
    {
        $code = $violation->getCode();

        $severity = match ($code) {
            FHIRViolationCode::WARNING => 'warning',
            FHIRViolationCode::INFO    => 'info',
            default                    => 'error',
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
        );
    }

    /**
     * Collects info-level violations for unpopulated must-support properties.
     *
     * Reflects over the resource's properties, finds those with #[FHIRMustSupport] attributes,
     * and generates info-level violations for properties that are empty or not set.
     *
     * @param object $resource The FHIR resource to inspect
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

            $value = $property->getValue($resource);

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
     * @param list<string>     $typeCandidates type-rooted dotted addresses for the current element
     * @param array<int, true> $visited        spl_object_id keys of already-visited objects (cycle guard)
     *
     * @return list<FHIRValidationViolation>
     */
    private function validateExtensionContexts(object $resource, string $fhirPath, string $relPath, array $typeCandidates, array &$visited): array
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

            foreach ($extensions as $extension) {
                $ref          = new \ReflectionClass($extension);
                $contextAttrs = array_map(
                    static fn (\ReflectionAttribute $a): FHIRExtensionContext => $a->newInstance(),
                    $ref->getAttributes(FHIRExtensionContext::class),
                );

                if ($contextAttrs !== []
                    && $this->classifyExtensionContexts($contextAttrs, $fhirPath, $resourceRoot, $typeCandidates, $ownSupertypes) === self::CONTEXT_DENY
                ) {
                    $url          = method_exists($extension, 'getExtensionUrl') ? ($extension->getExtensionUrl() ?? '') : '';
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

                $invariantAttrs = array_map(
                    static fn (\ReflectionAttribute $a): FHIRContextInvariant => $a->newInstance(),
                    $ref->getAttributes(FHIRContextInvariant::class),
                );

                foreach ($invariantAttrs as $invariant) {
                    try {
                        $result = $this->pathService->evaluate($invariant->expression, $resource);
                        $passed = $result->count() === 1 && $result->first() === true;
                    } catch (\Throwable) {
                        $passed = false;
                    }

                    if (!$passed) {
                        $url          = method_exists($extension, 'getExtensionUrl') ? ($extension->getExtensionUrl() ?? '') : '';
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
            }
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
                foreach ($this->validateExtensionContexts($value, $subFhirPath, $subRelPath, $childCandidates, $visited) as $v) {
                    $violations[] = $v;
                }
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        $childCandidates = $this->childTypeCandidates($item, $resource, $prop->getName(), $typeCandidates);
                        foreach ($this->validateExtensionContexts($item, $subFhirPath, $subRelPath . '[' . $i . ']', $childCandidates, $visited) as $v) {
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
     * @param list<string>               $typeCandidates
     * @param list<string>               $ownSupertypes
     *
     * @return self::CONTEXT_*
     */
    private function classifyExtensionContexts(array $contexts, string $fhirPath, string $resourceRoot, array $typeCandidates, array $ownSupertypes): int
    {
        $anyDeferred = false;

        foreach ($contexts as $ctx) {
            $verdict = $this->classifyContext($ctx, $fhirPath, $resourceRoot, $typeCandidates, $ownSupertypes);

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
     * inference — the resource type at the root, and same-resource-root path strings. Bare and
     * foreign-root contexts that do not match are deferred (never denied), so wiring a resolver
     * cannot create a false positive; it can only clear one.
     *
     * @param list<string> $typeCandidates type-rooted dotted addresses for this element
     * @param list<string> $ownSupertypes  FHIR type name of this element plus its supertypes
     *
     * @return self::CONTEXT_*
     */
    private function classifyContext(FHIRExtensionContext $ctx, string $fhirPath, string $resourceRoot, array $typeCandidates, array $ownSupertypes): int
    {
        if ($ctx->type !== 'element') {
            return self::CONTEXT_PERMIT; // fhirpath / extension contexts are permitted in v1
        }

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
            return $isRoot ? self::CONTEXT_DENY : self::CONTEXT_DEFER;
        }

        $exprRoot = substr($expr, 0, (int) strpos($expr, '.'));

        if ($exprRoot === $resourceRoot) {
            // Same resource root: a pure string comparison on the resource-rooted path,
            // definitive regardless of type resolution.
            return ($fhirPath === $expr || str_starts_with($fhirPath, $expr . '.'))
                ? self::CONTEXT_PERMIT
                : self::CONTEXT_DENY;
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
     * @param object $resource The FHIR resource to inspect
     * @param FHIRObligationContext $context The obligation context to match against
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

            $value   = $property->getValue($resource);
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
