<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\PropertyMetadataProvider;
use Ardenexal\FHIRTools\Component\Metadata\Type\PropertyMetadataProviderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FhirPropertyTypeHierarchyResolver;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRTypeHierarchyResolverInterface;
use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRContextInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRObligation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\IncompleteComplexExtensionRecorder;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\ObligationCode;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledExtension;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledProfile;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileFailure;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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

    /** @var \WeakMap<CompiledProfile, list<Constraint>> Runtime constraints per compiled profile */
    private \WeakMap $runtimeConstraints;

    /** @var \WeakMap<object, CompiledExtension|false> The runtime definition of each extension seen, false for none */
    private \WeakMap $runtimeExtensionOfObject;

    /** @var \WeakMap<CompiledExtension, list<object>> Context attributes per runtime extension definition */
    private \WeakMap $runtimeExtensionContexts;

    /** @var \WeakMap<CompiledExtension, list<Constraint>> Invariants and bindings per runtime extension definition */
    private \WeakMap $runtimeExtensionConstraints;

    /** @var array<class-string, bool> Whether each class seen by the runtime walk is a resource */
    private array $resourceClasses = [];

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly FHIRPathService $pathService,
        private readonly ?FHIRIGTypeRegistry $registry = null,
        private readonly FHIRTypeHierarchyResolverInterface $typeResolver = new FhirPropertyTypeHierarchyResolver(),
        private readonly FHIRValidationReportMapper $reportMapper = new FHIRValidationReportMapper(),
        private readonly NarrativeXhtmlChecker $narrativeChecker = new NarrativeXhtmlChecker(),
        private readonly PrimitiveFormatChecker $primitiveChecker = new PrimitiveFormatChecker(),
        private readonly CodingSystemChecker $codingSystemChecker = new CodingSystemChecker(),
        private readonly BundleEntryFullUrlChecker $bundleFullUrlChecker = new BundleEntryFullUrlChecker(),
        private readonly BundleReferenceResolutionChecker $bundleReferenceChecker = new BundleReferenceResolutionChecker(),
        private readonly UnknownInputChecker $unknownInputChecker = new UnknownInputChecker(),
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
        private readonly PropertyMetadataProviderInterface $properties = new PropertyMetadataProvider(),
        private readonly ComplexExtensionChecker $complexExtensionChecker = new ComplexExtensionChecker(),
        private readonly ?RuntimeProfileResolver $runtimeProfiles = null,
        private readonly DuplicateElementIdChecker $duplicateIdChecker = new DuplicateElementIdChecker(),
    ) {
        $this->runtimeConstraints          = new \WeakMap();
        $this->runtimeExtensionConstraints = new \WeakMap();
        $this->runtimeExtensionContexts    = new \WeakMap();
        $this->runtimeExtensionOfObject    = new \WeakMap();
    }

    /**
     * Validates a FHIR resource against constraints, extension contexts, and optional profiles.
     *
     * @param object                     $resource                The FHIR resource to validate
     * @param list<string>               $profileUrls             FHIR profile canonical URLs to validate against (empty array uses default constraints only)
     * @param bool                       $includeMustSupportInfo  When true, includes info-level violations for unpopulated must-support properties
     * @param FHIRObligationContext|null $obligationContext       When provided, applies obligation codes (SHALL_POPULATE, SHOULD_POPULATE, etc.)
     * @param bool                       $deriveProfilesFromClass When true, also validates against every profile the resource's own class declares,
     *                                                            including those it inherits. Off by default: it activates constraint groups the
     *                                                            caller did not ask for.
     *
     * @return FHIRValidationReport A structured report containing all violations found at all severity levels
     */
    public function validate(
        object $resource,
        array $profileUrls = [],
        bool $includeMustSupportInfo = false,
        ?FHIRObligationContext $obligationContext = null,
        bool $deriveProfilesFromClass = false,
    ): FHIRValidationReport {
        return $this->validateWithOptions($resource, new FHIRValidationOptions(
            profileUrls: $profileUrls,
            includeMustSupportInfo: $includeMustSupportInfo,
            obligationContext: $obligationContext,
            deriveProfilesFromClass: $deriveProfilesFromClass,
        ));
    }

    /**
     * Validates a FHIR resource with settings {@see validate()} does not take, such as `meta.profile` activation.
     *
     * A profile URL that the resource's class does not declare is checked against the
     * StructureDefinition the runtime profile resolver finds for it, when the service has one. Its
     * rules then apply to a base resource exactly as a generated profile class's attributes would.
     * A URL the resolver cannot use is reported as a warning naming the reason. Without a resolver,
     * such a requested URL is ignored as it always was, and such a `meta.profile` URL is reported as
     * not checked.
     *
     * @return FHIRValidationReport A structured report containing all violations found at all severity levels
     */
    public function validateWithOptions(object $resource, FHIRValidationOptions $options): FHIRValidationReport
    {
        $profileUrls             = $options->profileUrls;
        $includeMustSupportInfo  = $options->includeMustSupportInfo;
        $obligationContext       = $options->obligationContext;
        $deriveProfilesFromClass = $options->deriveProfilesFromClass;

        $groups = ['Default', ...$profileUrls];

        // Each pass asks for an extension's runtime definition; they share one answer per validation,
        // and a later validation sees definitions added to the registry since
        $this->runtimeExtensionOfObject = new \WeakMap();

        if ($deriveProfilesFromClass) {
            foreach ($this->declaredProfileUrls($resource) as $derived) {
                if (!in_array($derived, $groups, true)) {
                    $groups[] = $derived;
                }
            }
        }

        $rawViolations = $this->validator->validate($resource, null, $groups);

        $violations = [];
        foreach ($rawViolations as $rawViolation) {
            $violations[] = $this->mapViolation($rawViolation);
        }

        // Profiles the resource's class does not declare, checked against StructureDefinitions
        // resolved at runtime. A separate pass because their rules are not attributes on any class:
        // they are handed to the validator explicitly, under the same group names a generated class uses.
        // Without a resolver the walk still runs for `meta.profile`, whose profiles a nested resource's
        // generated class may declare without the pass above having run them
        if (($this->runtimeProfiles !== null && $profileUrls !== []) || $options->profilesFromMeta) {
            $walk = ['visited' => [], 'reached' => [], 'mismatched' => [], 'groups' => $groups];
            $this->collectRuntimeProfileViolations($resource, '', $profileUrls, $options, $violations, $walk);

            // A profile for another type is only a mistake when it reached nothing: asking for an
            // Observation profile on a Bundle is how its Observation entries get checked.
            foreach ($walk['mismatched'] as $canonical => $mismatch) {
                if (!isset($walk['reached'][$canonical])) {
                    $violations[] = $mismatch;
                }
            }
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

        // Narrative xhtml is checked here rather than by a constraint on the generated model: the
        // txt-1/txt-2 invariants reduce to a boolean, and the reference validator reports which
        // element or attribute is at fault alongside them. See NarrativeXhtmlChecker.
        foreach ($this->narrativeChecker->check($resource) as $narrativeViolation) {
            $violations[] = $narrativeViolation;
        }

        // Primitive lexemes the deserializer retained rather than aborting on. Unconditional, like
        // the pass above: the modifier-extension walk below is gated on a registry the conformance
        // harness never supplies, so a rule placed there would silently never run.
        foreach ($this->primitiveChecker->check($resource) as $primitiveViolation) {
            $violations[] = $primitiveViolation;
        }

        // Coding.system URI shape. Unconditional for the same reason as the two passes above, and
        // needs no terminology server: both rules read the URL alone. See CodingSystemChecker.
        foreach ($this->codingSystemChecker->check($resource) as $codingViolation) {
            $violations[] = $codingViolation;
        }

        // Element ids repeated within one resource. Unconditional like the passes above; it reads ids
        // alone. See DuplicateElementIdChecker.
        foreach ($this->duplicateIdChecker->check($resource) as $duplicateIdViolation) {
            $violations[] = $duplicateIdViolation;
        }

        // Elements the reader could not place on the model. Unconditional like the passes above,
        // and it reads what deserialization recorded rather than the object: by this point the
        // model holds only the names it could place, so the dropped ones are gone from it.
        foreach ($this->unknownInputChecker->check($resource) as $unknownInputViolation) {
            $violations[] = $unknownInputViolation;
        }

        // Complex extensions read as plain Extension because a required sub-extension was missing.
        // Unconditional, and read from what deserialization recorded, for the same reasons as the
        // pass above: the plain object no longer says it was meant to be the typed class.
        foreach ($this->complexExtensionChecker->check($resource) as $complexExtensionViolation) {
            $violations[] = $complexExtensionViolation;
        }

        // Bundle.entry.fullUrl rules: absolute, matching the resource when RESTful, and present where
        // the Bundle type or a relative reference needs it. Unconditional for the same reason again, and
        // it resolves nothing, so it is independent of the reference-resolution work. See
        // BundleEntryFullUrlChecker.
        foreach ($this->bundleFullUrlChecker->check($resource) as $fullUrlViolation) {
            $violations[] = $fullUrlViolation;
        }

        // A relative reference the fullUrl rules refuse to resolve to an entry the bundle does hold.
        // Unconditional like the pass above and resolves nothing off-box: it compares the reference
        // against the entries already in hand. See BundleReferenceResolutionChecker.
        foreach ($this->bundleReferenceChecker->check($resource) as $bundleReferenceViolation) {
            $violations[] = $bundleReferenceViolation;
        }

        // Extension.url is a structural rule, not a resolution one: it needs no registry, so unlike
        // the pass below it runs unconditionally. An extension without a url cannot be identified at
        // all, which is why the reference validator reports it even where it tolerates an extension
        // whose definition it merely cannot find.
        $urlVisited = [];
        foreach ($this->validateExtensionStructure($resource, '', $urlVisited, false) as $urlViolation) {
            $violations[] = $urlViolation;
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
     * Canonical URLs of every profile the resource's class declares, nearest ancestor last.
     *
     * A profile class carries #[FHIRProfileConstraint]s under its own canonical URL as the group,
     * and a derived profile inherits its parent's constraints without re-declaring them:
     * ObservationBpProfile holds `component` min=2, while `category`, `code`, `subject` and
     * `effective[x]` come from ObservationVitalsignsProfile and stay grouped under the *vitalsigns*
     * URL. Activating only the URL the instance names would leave every inherited rule inert, so the
     * whole chain is walked rather than just the leaf.
     *
     * A resource that is not a profile subclass yields nothing, which is what keeps the profile rules
     * silent on documents that declare no profile.
     *
     * @return list<string>
     */
    private function declaredProfileUrls(object $resource): array
    {
        $urls = [];

        foreach ($this->attributes->classAttributesInHierarchy($resource, FHIRProfile::class) as $profile) {
            if ($profile->profileUrl !== '' && !in_array($profile->profileUrl, $urls, true)) {
                $urls[] = $profile->profileUrl;
            }
        }

        return $urls;
    }

    /**
     * Validate one resource, and the resources nested in it, against profiles resolved at runtime.
     *
     * Mirrors how a requested group reaches generated profile classes. The requested URLs apply to
     * the top-level resource. A nested resource (a Bundle entry, a contained resource) gets a
     * requested URL only when its own `meta.profile` names it: a generated profile instance nested in
     * a Bundle is reached because it is an instance of that profile, not because of where it sits.
     * With `profilesFromMeta`, every resource is also checked against all its own `meta.profile` URLs.
     *
     * A URL the resource's class already declares stays with the generated attributes, so its rules
     * never run twice. When the pass over the whole resource did not run that URL's group (a
     * `meta.profile` URL on a nested typed instance, say), the class's own profiles are run here. `deriveProfilesFromClass` brings a requested profile's ancestors on the
     * top-level resource only, because on a generated class it adds the top-level class's own
     * profiles and nothing nested. A `meta.profile` URL always brings its ancestors, because a
     * resource that claims a profile claims all of it.
     *
     * @param list<string>                                                                                                                             $requested  URLs asked for at the top level; a nested resource works out its own
     * @param list<FHIRValidationViolation>                                                                                                            $violations appended to
     * @param array{visited: array<int, true>, reached: array<string, true>, mismatched: array<string, FHIRValidationViolation>, groups: list<string>} $walk
     *                                                                                                                                                             resources already walked by spl_object_id; requested canonicals a
     *                                                                                                                                                             nested resource took up; top-level type mismatches held back by
     *                                                                                                                                                             canonical; the groups the pass over the whole resource ran
     * @param object|null                                                                                                                              $root       The object validation started at; null at the start
     */
    private function collectRuntimeProfileViolations(
        object $resource,
        string $prefix,
        array $requested,
        FHIRValidationOptions $options,
        array &$violations,
        array &$walk,
        ?object $root = null,
    ): void {
        $root ??= $resource;
        if (isset($walk['visited'][spl_object_id($resource)])) {
            return;
        }
        $walk['visited'][spl_object_id($resource)] = true;
        $topLevel                                  = $prefix === '';

        $resourceAttributes = $this->attributes->classAttributesInHierarchy($resource, FhirResource::class);
        if ($resourceAttributes !== []) {
            $metaProfiles = $this->metaProfileUrls($resource);

            // A nested resource takes up a requested URL when it claims that profile, through its
            // meta.profile or its class, ignoring any |version on either side.
            if (!$topLevel) {
                $claimed   = [...$this->declaredProfileUrls($resource), ...array_map(self::canonicalWithoutVersion(...), $metaProfiles)];
                $requested = [];
                foreach ($options->profileUrls as $url) {
                    if (in_array(self::canonicalWithoutVersion($url), $claimed, true)) {
                        $requested[]                                          = $url;
                        $walk['reached'][self::canonicalWithoutVersion($url)] = true;
                    }
                }
            }

            /** @var array<string, array{ancestors: bool, fromMeta: bool}> $wanted URL as written => whether its ancestors apply, and whether meta.profile named it */
            $wanted = [];
            foreach ($requested as $url) {
                $wanted[$url] = ['ancestors' => $topLevel && $options->deriveProfilesFromClass, 'fromMeta' => false];
            }
            if ($options->profilesFromMeta) {
                foreach ($metaProfiles as $url) {
                    $wanted[$url] = ['ancestors' => true, 'fromMeta' => true];
                }
            }

            $mismatches = $this->validateAgainstRuntimeProfiles(
                $resource,
                $prefix,
                $resourceAttributes[0]->fhirVersion,
                $wanted,
                $walk['groups'],
                $violations,
                $root,
            );
            if ($topLevel) {
                $walk['mismatched'] += $mismatches;
            } else {
                array_push($violations, ...array_values($mismatches));
            }
        }

        foreach ($this->nestedResources($resource, $walk['visited']) as $nestedPath => $nested) {
            $this->collectRuntimeProfileViolations($nested, $prefix . $nestedPath . '.', [], $options, $violations, $walk, $root);
        }
    }

    /**
     * @param array<string, array{ancestors: bool, fromMeta: bool}> $wanted     URL as written => whether its constraint ancestors
     *                                                                          apply, and whether `meta.profile` named it
     * @param list<string>                                          $mainGroups The groups the pass over the whole resource ran
     * @param list<FHIRValidationViolation>                         $violations appended to
     * @param object                                                $root       The object validation started at, which $prefix is relative to
     *
     * @return array<string, FHIRValidationViolation> one error per wanted profile that constrains another type, by canonical
     */
    private function validateAgainstRuntimeProfiles(
        object $resource,
        string $prefix,
        string $fhirVersion,
        array $wanted,
        array $mainGroups,
        array &$violations,
        object $root,
    ): array {
        $mismatches = [];
        if ($wanted === []) {
            return $mismatches;
        }

        $declared     = $this->declaredProfileUrls($resource);
        $resourceType = $this->getResourceFhirType($resource);

        /** @var array<string, list<Constraint>> $constraintsByGroup */
        $constraintsByGroup = [];

        /** @var array<string, string> $checked canonical => the URL as written that is checked */
        $checked = [];

        /** @var array<string, CompiledProfile> $selected profile URL => the compiled version checked */
        $selected = [];

        /** @var array<string, true> $classGroups profiles the class declares that the pass over the whole resource did not run */
        $classGroups = [];

        foreach ($wanted as $url => $how) {
            $canonical = self::canonicalWithoutVersion($url);
            if (in_array($canonical, $declared, true)) {
                // The class's attributes check it. A requested URL's group already ran over the whole
                // resource; a meta.profile URL's group ran only if it was also requested, so a typed
                // nested instance claiming its profile would otherwise go unchecked. Claiming a
                // profile claims its ancestors too, so every profile the class declares runs.
                if ($how['fromMeta'] && !in_array($canonical, $mainGroups, true)) {
                    foreach ($declared as $group) {
                        if (!in_array($group, $mainGroups, true)) {
                            $classGroups[$group] = true;
                        }
                    }
                }

                continue;
            }

            if ($this->runtimeProfiles === null) {
                // Without a resolver a requested URL is ignored, as it always was; a meta.profile URL
                // is reported, because profilesFromMeta asks for every claimed profile to be checked
                if ($how['fromMeta']) {
                    $violations[] = new FHIRValidationViolation(
                        severity: 'warning',
                        path: rtrim($prefix, '.'),
                        message: sprintf("Profile reference '%s' has not been checked: no generated class declares it and no runtime profile resolver is configured", $url),
                        constraintClass: RuntimeProfileResolver::class,
                        profileGroup: $canonical,
                        invariantKey: null,
                        parameters: ['reason' => 'no-runtime-resolver'],
                        code: FHIRViolationCode::PROFILE_NOT_CHECKED,
                    );
                }

                continue;
            }

            // Both versions would share one validation group, so their rules would mix: check the
            // first and say the second was not checked.
            if (isset($checked[$canonical]) && $checked[$canonical] !== $url) {
                $violations[] = new FHIRValidationViolation(
                    severity: 'warning',
                    path: rtrim($prefix, '.'),
                    message: sprintf(
                        "Profile reference '%s' has not been checked: '%s' is already checked on this resource, and two versions of one profile cannot be checked together",
                        $url,
                        $checked[$canonical],
                    ),
                    constraintClass: RuntimeProfileResolver::class,
                    profileGroup: $canonical,
                    invariantKey: null,
                    parameters: ['reason' => 'version-conflict'],
                    code: FHIRViolationCode::PROFILE_NOT_CHECKED,
                );

                continue;
            }
            $checked[$canonical] = $url;

            $resolution = $this->runtimeProfiles->resolve($url, $fhirVersion);

            if (!$resolution->isResolved()) {
                $violations[] = new FHIRValidationViolation(
                    severity: 'warning',
                    path: rtrim($prefix, '.'),
                    message: $resolution->failure === RuntimeProfileFailure::Unknown
                        ? sprintf("Profile reference '%s' has not been checked because it is unknown", $url)
                        : sprintf("Profile reference '%s' has not been checked: %s", $url, $resolution->message),
                    constraintClass: RuntimeProfileResolver::class,
                    profileGroup: $canonical,
                    invariantKey: null,
                    parameters: ['reason' => $resolution->failure?->value],
                    code: FHIRViolationCode::PROFILE_NOT_CHECKED,
                );

                continue;
            }

            if ($resolution->rootType !== $resourceType) {
                $mismatches[$canonical] = new FHIRValidationViolation(
                    severity: 'error',
                    path: rtrim($prefix, '.'),
                    message: sprintf("Profile '%s' constrains %s, so it cannot be used to validate a %s", $url, (string) $resolution->rootType, $resourceType),
                    constraintClass: RuntimeProfileResolver::class,
                    profileGroup: $canonical,
                    invariantKey: null,
                    code: FHIRViolationCode::ERROR,
                );

                continue;
            }

            $chain = $resolution->chain;
            if (!$how['ancestors'] && $chain !== []) {
                $chain = [$chain[array_key_last($chain)]];
            }

            $applies = array_values(array_filter(
                $chain,
                static fn (CompiledProfile $profile): bool => !in_array($profile->profileUrl, $declared, true),
            ));

            // A chain that needs another version of a profile already selected here (a child of
            // parent|2 beside parent|1) would mix the two in one group, so it is not checked.
            $conflict = null;
            foreach ($applies as $profile) {
                if (isset($selected[$profile->profileUrl]) && $selected[$profile->profileUrl] !== $profile) {
                    $conflict = $profile->profileUrl;

                    break;
                }
            }
            if ($conflict !== null) {
                $violations[] = new FHIRValidationViolation(
                    severity: 'warning',
                    path: rtrim($prefix, '.'),
                    message: sprintf(
                        "Profile reference '%s' has not been checked: it needs a different version of '%s' than another profile checked on this resource",
                        $url,
                        $conflict,
                    ),
                    constraintClass: RuntimeProfileResolver::class,
                    profileGroup: $canonical,
                    invariantKey: null,
                    parameters: ['reason' => 'version-conflict'],
                    code: FHIRViolationCode::PROFILE_NOT_CHECKED,
                );

                continue;
            }

            foreach ($applies as $profile) {
                $selected[$profile->profileUrl] = $profile;
                $constraintsByGroup[$profile->profileUrl] ??= $this->runtimeConstraintsOf($profile);
            }
        }

        if ($classGroups !== []) {
            $this->appendValidatedWithin(
                $resource,
                $root,
                $prefix,
                fn (): ConstraintViolationListInterface => $this->validator->validate($resource, null, array_keys($classGroups)),
                $violations,
            );
        }

        if ($constraintsByGroup === []) {
            return $mismatches;
        }

        // The slice reader must serve the versions whose constraints run here.
        $this->runtimeProfiles?->activate($fhirVersion, $resourceType, array_values($selected));

        $this->appendValidatedWithin(
            $resource,
            $root,
            $prefix,
            fn (): ConstraintViolationListInterface => $this->validator->validate($resource, array_merge(...array_values($constraintsByGroup)), array_keys($constraintsByGroup)),
            $violations,
        );

        return $mismatches;
    }

    /**
     * Run one validation of a resource that may sit inside $root, and append its findings with
     * paths placed under $prefix.
     *
     * A nested resource is validated on its own, but its invariants see the resources around it,
     * as on a generated class reached from the root: a contained resource's %rootResource is its container.
     *
     * @param \Closure(): ConstraintViolationListInterface $validate
     * @param list<FHIRValidationViolation>                $violations appended to
     */
    private function appendValidatedWithin(object $resource, object $root, string $prefix, \Closure $validate, array &$violations): void
    {
        $raw = $prefix === '' ? $validate() : FHIRPathResourceContext::within($resource, $root, rtrim($prefix, '.'), $validate);
        foreach ($raw as $rawViolation) {
            $violation    = $this->mapViolation($rawViolation);
            $violations[] = $prefix === '' ? $violation : new FHIRValidationViolation(
                severity: $violation->severity,
                path: $violation->path === '' ? rtrim($prefix, '.') : $prefix . $violation->path,
                message: $violation->message,
                constraintClass: $violation->constraintClass,
                profileGroup: $violation->profileGroup,
                invariantKey: $violation->invariantKey,
                parameters: $violation->parameters,
                code: $violation->code,
            );
        }
    }

    /**
     * The compiled profile's constraints as instances, built once per profile.
     *
     * @return list<Constraint>
     */
    private function runtimeConstraintsOf(CompiledProfile $profile): array
    {
        if (isset($this->runtimeConstraints[$profile])) {
            return $this->runtimeConstraints[$profile];
        }

        $constraints = [];
        foreach ($profile->constraints() as $attribute) {
            $constraint = $attribute->instantiate();
            if ($constraint instanceof Constraint) {
                $constraints[] = $constraint;
            }
        }

        return $this->runtimeConstraints[$profile] = $constraints;
    }

    /**
     * The invariants and bindings a typed extension class carries, in the order its generator wrote them.
     *
     * @return list<Constraint>
     */
    private function extensionRuleAttributes(object $extension): array
    {
        return [
            ...$this->attributes->classAttributes($extension, FHIRProfileInvariant::class),
            ...$this->attributes->classAttributes($extension, FHIRProfileBinding::class),
        ];
    }

    /**
     * A runtime extension definition's invariants and bindings as instances, built once per definition.
     *
     * @return list<Constraint>
     */
    private function runtimeExtensionConstraintsOf(CompiledExtension $extension): array
    {
        if (isset($this->runtimeExtensionConstraints[$extension])) {
            return $this->runtimeExtensionConstraints[$extension];
        }

        $constraints = [];
        foreach ($extension->constraints as $attribute) {
            $constraint = $attribute->instantiate();
            if ($constraint instanceof Constraint) {
                $constraints[] = $constraint;
            }
        }

        return $this->runtimeExtensionConstraints[$extension] = $constraints;
    }

    /**
     * The canonical URLs a resource's `meta.profile` lists, as written.
     *
     * @return list<string>
     */
    private function metaProfileUrls(object $resource): array
    {
        $meta = self::readPublic($resource, 'meta');
        if (!is_object($meta)) {
            return [];
        }

        $urls = [];
        foreach ((array) self::readPublic($meta, 'profile') as $profile) {
            $value = is_object($profile) ? self::readPublic($profile, 'value') : $profile;
            if (is_string($value) && $value !== '') {
                $urls[] = $value;
            }
        }

        return $urls;
    }

    /**
     * The resources held inside this one, by property path, wherever they sit.
     *
     * Walks every public property down to the first resource on each branch: Bundle entries,
     * contained resources, `Parameters.parameter.resource`, `Bundle.entry.response.outcome`. Each
     * resource found walks its own children in turn, so nothing is visited twice.
     *
     * @param array<int, true> $visited spl_object_id of objects already walked, added to
     *
     * @return iterable<string, object>
     */
    private function nestedResources(object $subject, array &$visited, string $path = ''): iterable
    {
        foreach (get_object_vars($subject) as $property => $value) {
            $items = is_array($value) ? $value : ['' => $value];
            foreach ($items as $index => $item) {
                if (!is_object($item) || $item instanceof \UnitEnum || isset($visited[spl_object_id($item)])) {
                    continue;
                }

                $itemPath = $path . $property . ($index === '' ? '' : "[{$index}]");
                if ($this->isResource($item)) {
                    yield $itemPath => $item;

                    continue;
                }

                $visited[spl_object_id($item)] = true;
                yield from $this->nestedResources($item, $visited, $itemPath . '.');
            }
        }
    }

    private function isResource(object $subject): bool
    {
        return $this->resourceClasses[$subject::class] ??= $this->attributes->classAttributesInHierarchy($subject, FhirResource::class) !== [];
    }

    /**
     * A public property's value, or null when the object has no such property or it is unset.
     *
     * `??` reads through isset(), so an uninitialized typed property comes back as null instead of
     * throwing.
     */
    private static function readPublic(object $subject, string $property): mixed
    {
        return property_exists($subject, $property) ? ($subject->{$property} ?? null) : null;
    }

    private static function canonicalWithoutVersion(string $url): string
    {
        $pipe = strpos($url, '|');

        return $pipe === false ? $url : substr($url, 0, $pipe);
    }

    private function mapViolation(ConstraintViolationInterface $violation): FHIRValidationViolation
    {
        $code = $violation->getCode();

        $severity = match ($code) {
            FHIRViolationCode::WARNING             => 'warning',
            FHIRViolationCode::PROFILE_NOT_CHECKED => 'warning',
            FHIRViolationCode::INFO                => 'info',
            FHIRViolationCode::EVAL_ERROR          => 'info',
            FHIRViolationCode::UNCHECKED_BINDING   => 'info',
            default                                => 'error',
        };

        $constraint      = $violation->getConstraint();
        $constraintClass = $constraint !== null ? $constraint::class : '';

        $profileGroup = null;
        if (($constraint instanceof FHIRProfileConstraint || $constraint instanceof FHIRSliceConstraint
                                                          || $constraint instanceof FHIRProfileInvariant || $constraint instanceof FHIRProfileBinding)
            && $constraint->groups !== null
            && $constraint->groups !== []
            && $constraint->groups !== [Constraint::DEFAULT_GROUP]) {
            // An extension's invariants and bindings apply everywhere, in the default group, not a profile's
            $profileGroup = $constraint->groups[0];
        }

        $invariantKey = null;
        if ($constraint instanceof FHIRPathInvariant || $constraint instanceof FHIRProfileInvariant) {
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

        // Public properties only, where the raw reflection enumerated every visibility.
        foreach ($this->models->publicPropertyNames($resource) as $name) {
            if ($this->attributes->propertyAttributes($resource, $name, FHIRMustSupport::class) === []) {
                continue;
            }

            // Deserializers bypass the constructor, so an absent field is uninitialized (not null).
            // Treat uninitialized as not-populated so the must-support info still fires; the guarded
            // read answers null for both, which is exactly what this test wants.
            $value = $this->models->readInitializedValue($resource, $name);

            if ($value !== null && $value !== []) {
                continue;
            }

            $violations[] = new FHIRValidationViolation(
                severity: 'info',
                path: $name,
                message: sprintf('Must-support property "%s" is not populated.', $name),
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
                $definition   = $this->extensionDefinitionOf($extension);
                $contextAttrs = $this->extensionAttributes($extension, $definition, FHIRExtensionContext::class);

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
                            // A sub-extension sits on the carrying extension, not on the element.
                            $resource instanceof FHIRExtensionInterface ? $fhirPath . '.extension' : $fhirPath,
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
                $invariantAttrs = $resource instanceof FHIRExtensionInterface
                    ? []
                    : $this->extensionAttributes($extension, $definition, FHIRContextInvariant::class);

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

        foreach ($this->models->publicPropertyNames($resource) as $name) {
            if (in_array($name, ['extension', 'modifierExtension'], true)) {
                continue;
            }
            if (!$this->models->isPropertyInitialized($resource, $name)) {
                continue;
            }

            $value       = $this->models->readInitializedValue($resource, $name);
            $subFhirPath = $fhirPath . '.' . $name;
            $subRelPath  = $relPath !== '' ? $relPath . '.' . $name : $name;

            if (is_object($value)) {
                $childCandidates = $this->childTypeCandidates($value, $resource, $name, $typeCandidates);
                foreach ($this->validateExtensionContexts($value, $subFhirPath, $subRelPath, $childCandidates, $visited, []) as $v) {
                    $violations[] = $v;
                }
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        $childCandidates = $this->childTypeCandidates($item, $resource, $name, $typeCandidates);
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
     * contexts on an extension nested one level inside another deny on mismatch, because the
     * carrier's path is then known (see classifyNestedElementContext()); deeper levels defer.
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

        $bearingIsExtension = $bearingElement instanceof FHIRExtensionInterface;

        if ($bearingIsExtension) {
            return $this->classifyNestedElementContext($ctx->expression, $fhirPath, $ownSupertypes, $ancestorExtensionUrls);
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
            if ($fhirPath === $expr || str_starts_with($fhirPath, $expr . '.')) {
                return self::CONTEXT_PERMIT;
            }

            return self::CONTEXT_DENY;
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
     * Classify an element context for an extension that sits inside another extension.
     *
     * The reference validator places such an extension at two addresses: the type `Extension`
     * and the path of the extension that carries it, e.g. `[Extension, Patient.extension]` for a
     * sub-extension of an extension on Patient. An element context permits it only when it names
     * one of those. So `bodySite` (contexts `Condition.bodySite`, `Dosage.site`, ...) nested in
     * `patient-animal` on Patient is denied, as the reference validator denies it.
     *
     * Denial needs the carrying extension's path to be known for certain, which holds one level
     * down from an element: the carrier is `<element>.extension`. Deeper levels, and a chain that
     * could not be assembled, defer unless the context names the type.
     *
     * @param string            $expr                  the element context expression
     * @param string            $fhirPath              resource-rooted path of the element bearing the carrier
     * @param list<string>      $ownSupertypes         FHIR type name of the carrier plus its supertypes
     * @param list<string>|null $ancestorExtensionUrls enclosing extension-URL chain, carrier last
     *
     * @return self::CONTEXT_*
     */
    private function classifyNestedElementContext(string $expr, string $fhirPath, array $ownSupertypes, ?array $ancestorExtensionUrls): int
    {
        // Extension and its supertypes, whether or not a type resolver supplied them.
        if (in_array($expr, ['Extension', 'Element', 'DataType', 'Base', ...$ownSupertypes], true)) {
            return self::CONTEXT_PERMIT;
        }

        if ($ancestorExtensionUrls === null || count($ancestorExtensionUrls) !== 1) {
            return self::CONTEXT_DEFER;
        }

        // The carrier is either an extension or a modifierExtension of the element; which one is
        // not known here, so both addresses permit.
        foreach (['extension', 'modifierExtension'] as $carrierProperty) {
            if ($expr === $fhirPath . '.' . $carrierProperty) {
                return self::CONTEXT_PERMIT;
            }
        }

        return self::CONTEXT_DENY;
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
     * Where an extension's context rules are declared: its own class, or the typed class it could not become.
     *
     * A complex extension missing a required sub-extension is read as plain `Extension`, which
     * declares no contexts. Its typed class still says where it may appear, so placement is checked
     * against that rather than silently passing.
     *
     * @param object $extension the extension as read
     *
     * @return object|class-string the object, or the typed class recorded for it
     */
    private function extensionDefinitionOf(object $extension): object|string
    {
        $records = IncompleteComplexExtensionRecorder::forObject($extension);

        return $records !== [] ? $records[0]->extensionClass : $extension;
    }

    /**
     * An extension's context attributes: its class's own, or those of the definition its url names at runtime.
     *
     * @template T of object
     *
     * @param object              $extension      the extension as read
     * @param object|class-string $definition     where its rules are declared, from {@see extensionDefinitionOf()}
     * @param class-string<T>     $attributeClass FHIRExtensionContext or FHIRContextInvariant
     *
     * @return list<T>
     */
    private function extensionAttributes(object $extension, object|string $definition, string $attributeClass): array
    {
        $declared = $this->attributes->classAttributes($definition, $attributeClass);
        if ($declared !== [] || $definition !== $extension) {
            return $declared;
        }

        $runtime = $this->runtimeExtensionOf($extension);
        if ($runtime === null) {
            return [];
        }

        if (!isset($this->runtimeExtensionContexts[$runtime])) {
            $this->runtimeExtensionContexts[$runtime] = array_map(
                static fn ($attribute): object => $attribute->instantiate(),
                $runtime->contexts,
            );
        }

        $matching = [];
        foreach ($this->runtimeExtensionContexts[$runtime] as $attribute) {
            if ($attribute instanceof $attributeClass) {
                $matching[] = $attribute;
            }
        }

        return $matching;
    }

    /**
     * The definition loaded at runtime for a plain `Extension`, when its url names one.
     *
     * A typed extension class, or a plain one read in place of a typed class (see
     * {@see extensionDefinitionOf()}), already has its rules, so it gets none from here: a definition
     * is never applied twice.
     */
    private function runtimeExtensionOf(object $extension): ?CompiledExtension
    {
        if (isset($this->runtimeExtensionOfObject[$extension])) {
            $known = $this->runtimeExtensionOfObject[$extension];

            return $known === false ? null : $known;
        }

        $resolved                                   = $this->resolveRuntimeExtension($extension);
        $this->runtimeExtensionOfObject[$extension] = $resolved ?? false;

        return $resolved;
    }

    private function resolveRuntimeExtension(object $extension): ?CompiledExtension
    {
        if ($this->runtimeProfiles === null
            || !$extension instanceof FHIRExtensionInterface
            || $this->attributes->declaresInHierarchy($extension, FHIRExtensionDefinition::class)
            || IncompleteComplexExtensionRecorder::forObject($extension) !== []
        ) {
            return null;
        }

        $url     = $this->readExtensionUrl($extension);
        $release = $this->releaseOf($extension);
        if ($url === '' || $release === null || !$this->isAbsoluteUrl($url)) {
            return null;
        }

        return $this->runtimeProfiles->resolveExtension($url, $release);
    }

    /**
     * The FHIR release a model object belongs to, from its class's type attribute.
     */
    private function releaseOf(object $element): ?string
    {
        $types = $this->attributes->classAttributesInHierarchy($element, FHIRComplexType::class);

        return $types !== [] ? $types[0]->fhirVersion : null;
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
     * Reads the most-derived FHIR type name in the class hierarchy, so a profile subclass that
     * declares no #[FhirResource] of its own reports the resource type it profiles rather than its
     * PHP class name. The previous read looked at the concrete class only and then guessed from the
     * class name, which for `AUBasePatientProfile` produced `AUBasePatientProfile` — a type name no
     * extension context can ever match, so every context check on a profiled resource passed by
     * failing to apply.
     *
     * The class-name fallback stays for an object the hierarchy cannot name at all.
     *
     * @param object $resource The FHIR resource object to inspect
     *
     * @return string The FHIR type name (e.g., "Patient", "Observation", "Bundle")
     */
    private function getResourceFhirType(object $resource): string
    {
        $hierarchy = $this->typeResolver->resolveTypeHierarchy($resource);

        if ($hierarchy !== []) {
            return $hierarchy[0];
        }

        $parts = explode('\\', $resource::class);
        $name  = (string) end($parts);

        return str_ends_with($name, 'Resource') ? substr($name, 0, -8) : $name;
    }

    /**
     * Walk $resource and every nested object, reporting extensions that are structurally wrong.
     *
     * Three rules, all structural, each worded as the reference validator words them so the comparison
     * pairs them by text:
     *
     * - no readable `url` at any depth — `Extension.url is required in order to identify, use and
     *   validate the extension`;
     * - a top-level extension whose `url` is not absolute — `Extension.url must be an absolute URL`;
     * - a value whose type the extension's own definition does not allow, reported by
     *   {@see extensionValueTypeMismatch()} from a typed class or from a definition loaded at runtime.
     *
     * A plain extension whose definition was loaded at runtime is also checked for a missing required
     * sub-extension and for undeclared ones here, as {@see ComplexExtensionChecker} checks a typed one.
     * Every extension is held to its definition's invariants and bindings here: a typed class's
     * attributes, or a plain extension's runtime definition. Symfony does not cascade into extensions,
     * so nothing else runs them. They run as if from $root at the extension's path, so `%resource` is
     * the resource around the extension.
     *
     * The absoluteness rule deliberately stops at the first level. A *sub*-extension's url is a slice
     * name rather than a canonical (`x-breed`, `species-x`), so requiring absoluteness there would
     * invent findings on every correctly-formed complex extension. $insideExtension carries that.
     *
     * The url is read through {@see readExtensionUrl()}, which treats an uninitialized typed property
     * as absent rather than letting the `Error` escape. That is the same thing here, since an
     * extension whose url never deserialized has no url to validate against.
     *
     * @param array<int, true> $visited spl_object_id keys of already-visited objects (cycle guard)
     * @param object|null      $root    The object validation started at, which $path is relative to; null at the start
     *
     * @return list<FHIRValidationViolation>
     */
    private function validateExtensionStructure(object $resource, string $path, array &$visited, bool $insideExtension, ?object $root = null): array
    {
        $root ??= $resource;
        $id = spl_object_id($resource);

        if (isset($visited[$id])) {
            return [];
        }

        $visited[$id] = true;
        $violations   = [];

        foreach (['extension', 'modifierExtension'] as $property) {
            if (!property_exists($resource, $property) || !isset($resource->$property) || !is_array($resource->$property)) {
                continue;
            }

            $extPath = $path !== '' ? $path . '.' . $property : $property;

            foreach ($resource->$property as $extIndex => $ext) {
                if (!is_object($ext)) {
                    continue;
                }

                // Sub-extensions a typed complex extension does not declare. Checked on this walk
                // because it already visits every extension, so clean documents pay nothing extra.
                if ($ext instanceof FHIRComplexExtensionInterface) {
                    foreach ($this->complexExtensionChecker->checkUndeclaredSubExtensions($ext, $extPath . '[' . $extIndex . ']') as $undeclared) {
                        $violations[] = $undeclared;
                    }
                }

                $url = $this->readExtensionUrl($ext);

                if ($url === '') {
                    $violations[] = new FHIRValidationViolation(
                        severity: 'error',
                        path: $extPath,
                        message: 'Extension.url is required in order to identify, use and validate the extension',
                        constraintClass: self::class,
                        profileGroup: null,
                        invariantKey: null,
                    );
                } elseif (!$insideExtension && !$this->isAbsoluteUrl($url)) {
                    $violations[] = new FHIRValidationViolation(
                        severity: 'error',
                        path: $extPath,
                        message: 'Extension.url must be an absolute URL',
                        constraintClass: self::class,
                        profileGroup: null,
                        invariantKey: null,
                    );
                }

                // A plain extension whose definition was loaded at runtime is held to that
                // definition's sub-extensions, as its generated class would be
                $runtime = $this->runtimeExtensionOf($ext);
                $release = $this->releaseOf($ext);
                if ($runtime !== null && $release !== null && $ext instanceof FHIRExtensionInterface) {
                    foreach ($this->complexExtensionChecker->checkAgainstDefinition($ext, $runtime, $release, $extPath . '[' . $extIndex . ']') as $subViolation) {
                        $violations[] = $subViolation;
                    }
                }

                // Invariants and bindings from the extension's definition. Symfony does not cascade into
                // extensions, so a typed class's attributes are run here too, the same way as a plain
                // extension's runtime definition
                $rules = $runtime !== null ? $this->runtimeExtensionConstraintsOf($runtime) : $this->extensionRuleAttributes($ext);
                if ($rules !== []) {
                    $at  = $extPath . '[' . $extIndex . ']';
                    $raw = FHIRPathResourceContext::within(
                        $ext,
                        $root,
                        $at,
                        fn () => $this->validator->validate($ext, $rules, ['Default']),
                    );
                    foreach ($raw as $rawViolation) {
                        $violation    = $this->mapViolation($rawViolation);
                        $violations[] = new FHIRValidationViolation(
                            severity: $violation->severity,
                            path: $violation->path === '' ? $at : $at . '.' . $violation->path,
                            message: $violation->message,
                            constraintClass: $violation->constraintClass,
                            profileGroup: $violation->profileGroup,
                            invariantKey: $violation->invariantKey,
                            parameters: $violation->parameters,
                            code: $violation->code,
                        );
                    }
                }

                $typeMismatch = $this->extensionValueTypeMismatch($ext, $url);

                if ($typeMismatch !== null) {
                    $violations[] = new FHIRValidationViolation(
                        severity: 'error',
                        path: $extPath,
                        message: $typeMismatch,
                        constraintClass: self::class,
                        profileGroup: null,
                        invariantKey: null,
                    );
                }
            }
        }

        foreach ($this->models->publicPropertyNames($resource) as $name) {
            if (!$this->models->isPropertyInitialized($resource, $name)) {
                continue;
            }

            $isExtProp  = $name === 'extension' || $name === 'modifierExtension';
            $childInExt = $insideExtension      || $isExtProp;
            $value      = $this->models->readInitializedValue($resource, $name);
            $propPath   = $path !== '' ? $path . '.' . $name : $name;

            if (is_object($value)) {
                foreach ($this->validateExtensionStructure($value, $propPath, $visited, $childInExt, $root) as $v) {
                    $violations[] = $v;
                }
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        foreach ($this->validateExtensionStructure($item, $propPath . '[' . $i . ']', $visited, $childInExt, $root) as $v) {
                            $violations[] = $v;
                        }
                    }
                }
            }
        }

        return $violations;
    }

    /**
     * The reference validator's sentence when an extension's value is not a type its definition allows.
     *
     * A typed extension class, or a plain one whose definition was loaded at runtime
     * ({@see runtimeAllowedValueTypes()}), can answer this. A generated class carries
     * `#[FHIRExtensionDefinition]` and narrows the value in its constructor, as in
     * `NarrativeLinkExtension::__construct(public ?UrlPrimitive $valueUrl = null)`, so the allowed
     * types are its `value*` promoted parameters and nothing else has to be looked up. An extension
     * that deserialized as the base `Extension` with no runtime definition either yields null: with no
     * definition in hand there is no allowed set to compare against, and guessing one would invent
     * findings the reference validator does not report.
     *
     * The FHIR type name comes from `#[FHIRPrimitive(primitiveType: ...)]` rather than from the PHP
     * class name: the attribute is the generator's own declaration of the FHIR type a class carries,
     * which a class name only ever resembles by convention.
     *
     * @return string|null the message, or null when the value is allowed or nothing can be decided
     */
    private function extensionValueTypeMismatch(object $extension, string $url): ?string
    {
        if ($this->attributes->classAttributes($extension, FHIRExtensionDefinition::class) === []) {
            $runtime = $this->runtimeExtensionOf($extension);

            return $runtime === null ? null : $this->valueTypeMismatch($extension, $url, $this->runtimeAllowedValueTypes($runtime));
        }

        $allowed = [];

        // Metadata-driven, and the map DOES span the hierarchy: PropertyMetadataProvider merges every
        // ancestor's constructor parameters, child overriding parent. That is safe here only because
        // the inherited `value…` slot is the base extension's choice, which the isChoice test below
        // discards -- not because the walk stops at this class. A base class gaining a non-choice
        // `value…` parameter would widen `$allowed` for every subclass, so the filter is what keeps
        // this correct, not the lookup.
        //
        // Reading the map rather than the constructor also drops the old `isPromoted()` requirement.
        // The generator forwards `value…` to `parent::__construct()` instead of promoting it, so that
        // check had been silently skipping the entire allowed-type test on 223 of 2144 generated
        // extension classes; those are now checked. No class lost a check, and for the 1581 already
        // checked the allowed set is unchanged.
        //
        // A choice slot is skipped deliberately. Its declared type is a union, and a union names no
        // single type to allow -- collapsing it to one member would allow that member and reject its
        // siblings, which is how `valueCanonical` came to be reported against `[base64Binary]`.
        foreach ($this->properties->getPropertyMetadata($extension::class) as $property => $meta) {
            if (!str_starts_with($property, 'value') || $meta->isChoice) {
                continue;
            }

            // The metadata already carries the FHIR type name the generator declared, so there is no
            // class to resolve back into a name. A System.* URL names no FHIR type and is skipped.
            $name = $meta->fhirType;

            if ($name !== '' && !str_contains($name, '://')) {
                $allowed[] = $name;
            }
        }

        return $this->valueTypeMismatch($extension, $url, $allowed);
    }

    /**
     * The value types a generated class of this definition would allow, as {@see extensionValueTypeMismatch()} reads them.
     *
     * The generator gives a simple extension with one value type a `value<Type>` parameter, which
     * names its type, and one with several a choice parameter, which the check skips. A complex
     * extension has no value parameter at all. So only a simple extension with one type has an
     * allowed set, and not when that type is a `System.*` url, which names no FHIR type.
     *
     * @return list<string>
     */
    private function runtimeAllowedValueTypes(CompiledExtension $definition): array
    {
        if ($definition->isComplex() || count($definition->valueTypes) !== 1 || str_contains($definition->valueTypes[0], '://')) {
            return [];
        }

        return $definition->valueTypes;
    }

    /**
     * @param list<string> $allowed FHIR type names the value may have; empty when nothing can be decided
     */
    private function valueTypeMismatch(object $extension, string $url, array $allowed): ?string
    {
        if ($allowed === [] || !isset($extension->value) || !is_object($extension->value)) {
            return null;
        }

        $found = $this->fhirTypeNameOfClass($extension->value::class);

        if ($found === null || in_array($found, $allowed, true)) {
            return null;
        }

        return sprintf(
            "The Extension '%s' definition allows for the types [%s] but found type %s",
            $url,
            implode(', ', $allowed),
            $found,
        );
    }

    /**
     * The FHIR type name a model class represents, or null when the class does not say.
     *
     * Primitives answer through `#[FHIRPrimitive]`; complex types are named by the class itself.
     */
    private function fhirTypeNameOfClass(string $class): ?string
    {
        if (!class_exists($class)) {
            return null;
        }

        $primitives = $this->attributes->classAttributes($class, FHIRPrimitive::class);

        if ($primitives !== []) {
            return $primitives[0]->primitiveType;
        }

        $parts = explode('\\', $class);

        return (string) end($parts);
    }

    /**
     * Is this url absolute, in the sense the reference validator means?
     *
     * A scheme followed by `:` is the whole test. Deliberately not `filter_var(..., FILTER_VALIDATE_URL)`:
     * FHIR canonicals include urn forms (`urn:uuid:`, `urn:oid:`) that filter_var rejects, and rejecting
     * those would report findings the reference validator does not.
     */
    private function isAbsoluteUrl(string $url): bool
    {
        return preg_match('/^[A-Za-z][A-Za-z0-9+.-]*:/', $url) === 1;
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

                // readExtensionUrl(), not getExtensionUrl(): deserializer-origin objects leave the
                // promoted typed $url uninitialized, and a bare read throws an Error that would take
                // the whole case out of the comparison as `validate-crashed`, lowering the apparent
                // gap while fixing nothing. See footguns/model-object-initialization.md.
                $url = $this->readExtensionUrl($ext);

                if ($url === '') {
                    continue;
                }

                /** @var FHIRIGTypeRegistry $registry */
                $registry = $this->registry;

                if ($registry->resolveExtensionClass($url) === null && $this->runtimeExtensionOf($ext) === null) {
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

        foreach ($this->models->publicPropertyNames($resource) as $name) {
            if ($name === 'modifierExtension') {
                continue;
            }
            if (!$this->models->isPropertyInitialized($resource, $name)) {
                continue;
            }

            $value    = $this->models->readInitializedValue($resource, $name);
            $propPath = $path !== '' ? $path . '.' . $name : $name;

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

        // Public properties only; see the note in collectMustSupportInfo. The same 12 non-public
        // properties carry no #[FHIRObligation] either, so none of them ever reached the body.
        foreach ($this->models->publicPropertyNames($resource) as $name) {
            $attrs = $this->attributes->propertyAttributes($resource, $name, FHIRObligation::class);

            if ($attrs === []) {
                continue;
            }

            // Uninitialized (constructor-bypassed deserialization) counts as absent → empty, so the
            // obligation still fires; the guarded read answers null for uninitialized and for null.
            $value   = $this->models->readInitializedValue($resource, $name);
            $isEmpty = $value === null || $value === [];

            foreach ($attrs as $obligation) {
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
                    path: $name,
                    message: sprintf('Obligation %s: property "%s" must be populated.', $obligation->code, $name),
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

        foreach ($this->models->publicPropertyNames($resource) as $name) {
            foreach ($this->attributes->propertyAttributes($resource, $name, FHIRObligation::class) as $obligation) {
                if ($context->matchesObligation($obligation)
                    && ObligationCode::tryFrom($obligation->code) === ObligationCode::SHALL_NO_ERROR
                ) {
                    $suppressedPaths[] = $name;
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
