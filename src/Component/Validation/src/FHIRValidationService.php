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

final class FHIRValidationService implements FHIRValidationServiceInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly FHIRPathService $pathService,
        private readonly ?FHIRIGTypeRegistry $registry = null,
        private readonly FHIRTypeHierarchyResolverInterface $typeResolver = new NullFHIRTypeHierarchyResolver(),
    ) {
    }

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
        foreach ($this->validateExtensionContexts($resource, $this->getResourceFhirType($resource), '', $contextVisited) as $contextViolation) {
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

    /** @return list<FHIRValidationViolation> */
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

    /** @return list<FHIRValidationViolation> */
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
     * Pass 2: check extension context and contextInvariant constraints for all extensions
     * throughout the resource tree. Walks nested sub-elements recursively.
     *
     * At sub-element level (path contains a dot), only extensions whose context expression
     * is a dotted path sharing the current root resource type are checked. Bare type-name
     * contexts (e.g. "HumanName") and foreign-root paths (e.g. "ElementDefinition.binding"
     * inside StructureDefinition) require FHIR type-hierarchy resolution — deferred.
     *
     * @param array<int, true> $visited spl_object_id keys of already-visited objects (cycle guard)
     *
     * @return list<FHIRValidationViolation>
     */
    private function validateExtensionContexts(object $resource, string $fhirPath, string $relPath, array &$visited, ?string $resolvedFhirType = null): array
    {
        $id = spl_object_id($resource);

        if (isset($visited[$id])) {
            return [];
        }

        $visited[$id] = true;
        $violations   = [];

        if (method_exists($resource, 'getExtensions')) {
            $extViolationPath = $relPath !== '' ? $relPath . '.extension' : 'extension';

            // At sub-element level, determine the root resource type so that only context
            // expressions explicitly targeting this resource's path hierarchy are evaluated.
            // Contexts without a dot or with a different root type require type-hierarchy
            // resolution and are deferred. At root level ($rootType === null) all
            // expressions are checked as before.
            $dotPos   = strpos($fhirPath, '.');
            $rootType = $dotPos !== false ? substr($fhirPath, 0, $dotPos) : null;

            /** @var list<object> $extensions */
            $extensions = $resource->getExtensions();

            foreach ($extensions as $extension) {
                $ref          = new \ReflectionClass($extension);
                $contextAttrs = array_map(
                    static fn (\ReflectionAttribute $a): FHIRExtensionContext => $a->newInstance(),
                    $ref->getAttributes(FHIRExtensionContext::class),
                );

                // Sub-element filter: contexts that cannot be evaluated without type-hierarchy
                // resolution are deferred when no resolved type is available.
                //
                // Bare-type contexts (no dot, e.g. "HumanName") require knowing the FHIR type
                // of the current element's property.
                // Foreign-root paths (dotted, different root, e.g. "ElementDefinition.binding")
                // also require type resolution: in FHIR, "ElementDefinition.binding" means
                // "the binding property of any ElementDefinition element wherever it appears",
                // not just in the ElementDefinition resource. Evaluating this correctly requires
                // walking the type path (e.g. knowing that Patient.name is typed HumanName),
                // which is the same resolution problem as bare-type contexts.
                //
                // When $resolvedFhirType is provided, the skip is lifted and contextPermitsPath()
                // evaluates what it can (bare-type match + path match).
                // contextInvariant evaluation always runs regardless.
                $skipContextCheck = false;
                if ($rootType !== null && $contextAttrs !== []) {
                    $hasCheckable = false;
                    foreach ($contextAttrs as $ctx) {
                        if ($ctx->type === 'element'
                            && str_contains($ctx->expression, '.')
                            && str_starts_with($ctx->expression, $rootType . '.')
                        ) {
                            $hasCheckable = true;
                            break;
                        }
                    }
                    // Skip when no same-root context is checkable and no resolved type is available.
                    // This covers both bare-type (e.g. "HumanName") and foreign-root (e.g.
                    // "ElementDefinition.binding") contexts, which both require type-hierarchy
                    // resolution to evaluate correctly. A resolved type lifts the skip.
                    $skipContextCheck = !$hasCheckable && $resolvedFhirType === null;
                }

                if (!$skipContextCheck && $contextAttrs !== [] && !$this->contextPermitsPath($contextAttrs, $fhirPath, $resolvedFhirType)) {
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

            $value           = $prop->getValue($resource);
            $subFhirPath     = $fhirPath . '.' . $prop->getName();
            $subRelPath      = $relPath !== '' ? $relPath . '.' . $prop->getName() : $prop->getName();
            $subResolvedType = $this->typeResolver->resolvePropertyType($resource, $prop->getName());

            if (is_object($value)) {
                foreach ($this->validateExtensionContexts($value, $subFhirPath, $subRelPath, $visited, $subResolvedType) as $v) {
                    $violations[] = $v;
                }
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        foreach ($this->validateExtensionContexts($item, $subFhirPath, $subRelPath . '[' . $i . ']', $visited, $subResolvedType) as $v) {
                            $violations[] = $v;
                        }
                    }
                }
            }
        }

        return $violations;
    }

    /**
     * Returns true if at least one context entry permits the given element path.
     *
     * For type=element with a dotted expression: permits by exact or prefix path match.
     * For type=element with a bare type name: permits when $resolvedFhirType matches.
     * For type=fhirpath and type=extension: deferred in v1, treated as permitted.
     *
     * @param list<FHIRExtensionContext> $contexts
     */
    private function contextPermitsPath(array $contexts, string $elementPath, ?string $resolvedFhirType = null): bool
    {
        foreach ($contexts as $ctx) {
            if ($ctx->type !== 'element') {
                return true; // fhirpath and extension types deferred in v1
            }

            // Path equality covers both root-level paths ("Patient") and dotted paths.
            if ($elementPath === $ctx->expression) {
                return true;
            }

            if (str_contains($ctx->expression, '.')) {
                if (str_starts_with($elementPath, $ctx->expression . '.')) {
                    return true;
                }
                continue;
            }

            // No dot and no path match: treat as a bare FHIR type name — match against
            // the resolved type of this element (e.g. "HumanName" on Patient.name).
            if ($resolvedFhirType !== null && $ctx->expression === $resolvedFhirType) {
                return true;
            }
        }

        return false;
    }

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
}
