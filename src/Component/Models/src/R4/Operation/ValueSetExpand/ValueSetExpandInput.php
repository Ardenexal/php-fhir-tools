<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ValueSetExpand;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSetResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/ValueSet-expand',
    use: 'in',
    version: 'R4',
    operation: 'ValueSetExpand',
    path: '',
)]
final class ValueSetExpandInput
{
    /**
     * @param list<string> $designation
     * @param list<string> $excludeSystem
     * @param list<string> $systemVersion
     * @param list<string> $checkSystemVersion
     * @param list<string> $forceSystemVersion
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'url',
            phpName: 'url',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'A canonical reference to a value set. The server must know the value set (e.g. it is defined explicitly in the server\'s value sets, or it is defined implicitly by some code system known to the server',
        )]
        public readonly ?string $url = null,
        #[FhirOperationParameter(
            name: 'valueSet',
            phpName: 'valueSet',
            use: 'in',
            min: 0,
            max: '1',
            type: 'ValueSet',
            documentation: 'The value set is provided directly as part of the request. Servers may choose not to accept value sets in this fashion',
        )]
        public readonly ?ValueSetResource $valueSet = null,
        #[FhirOperationParameter(
            name: 'valueSetVersion',
            phpName: 'valueSetVersion',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: 'The identifier that is used to identify a specific version of the value set to be used when generating the expansion. This is an arbitrary value managed by the value set author and is not expected to be globally unique. For example, it might be a timestamp (e.g. yyyymmdd) if a managed version is not available.',
        )]
        public readonly ?string $valueSetVersion = null,
        #[FhirOperationParameter(
            name: 'context',
            phpName: 'context',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
            documentation: 'The context of the value set, so that the server can resolve this to a value set to expand. The recommended format for this URI is [Structure Definition URL]#[name or path into structure definition] e.g. http://hl7.org/fhir/StructureDefinition/observation-hspc-height-hspcheight#Observation.interpretation. Other forms may be used but are not defined. This form is only usable if the terminology server also has access to the conformance registry that the server is using, but can be used to delegate the mapping from an application context to a binding at run-time',
        )]
        public readonly ?string $context = null,
        #[FhirOperationParameter(
            name: 'contextDirection',
            phpName: 'contextDirection',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: "If a context is provided, a context direction may also be provided. Valid values are: \n\n* 'incoming': the codes a client can use for PUT/POST operations,  and \n* 'outgoing', the codes a client might receive from the server.\n\nThe purpose is to inform the server whether to use the value set associated with the context for reading or writing purposes (note: for most elements, this is the same value set, but there are a few elements where the reading and writing value sets are different)",
        )]
        public readonly ?string $contextDirection = null,
        #[FhirOperationParameter(
            name: 'filter',
            phpName: 'filter',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            documentation: "A text filter that is applied to restrict the codes that are returned (this is useful in a UI context). The interpretation of this is delegated to the server in order to allow to determine the most optimal search approach for the context. The server can document the way this parameter works in [TerminologyCapabilities](terminologycapabilities.html)..expansion.textFilter. Typical usage of this parameter includes functionality like:\n\n* using left matching e.g. \"acut ast\"\n* allowing for wild cards such as %, &, ?\n* searching on definition as well as display(s)\n* allowing for search conditions (and / or / exclusions)\n\nText Search engines such as Lucene or Solr, long with their considerable functionality, might also be used. The optional text search might also be code system specific, and servers might have different implementations for different code systems",
        )]
        public readonly ?string $filter = null,
        #[FhirOperationParameter(
            name: 'date',
            phpName: 'date',
            use: 'in',
            min: 0,
            max: '1',
            type: 'dateTime',
            documentation: 'The date for which the expansion should be generated.  if a date is provided, it means that the server should use the value set / code system definitions as they were on the given date, or return an error if this is not possible.  Normally, the date is the current conditions (which is the default value) but under some circumstances, systems need to generate an expansion as it would have been in the past. A typical example of this would be where code selection is constrained to the set of codes that were available when the patient was treated, not when the record is being edited. Note that which date is appropriate is a matter for implementation policy.',
        )]
        public readonly ?string $date = null,
        #[FhirOperationParameter(
            name: 'offset',
            phpName: 'offset',
            use: 'in',
            min: 0,
            max: '1',
            type: 'integer',
            documentation: 'Paging support - where to start if a subset is desired (default = 0). Offset is number of records (not number of pages)',
        )]
        public readonly ?int $offset = null,
        #[FhirOperationParameter(
            name: 'count',
            phpName: 'count',
            use: 'in',
            min: 0,
            max: '1',
            type: 'integer',
            documentation: 'Paging support - how many codes should be provided in a partial page view. Paging only applies to flat expansions - servers ignore paging if the expansion is not flat.  If count = 0, the client is asking how large the expansion is. Servers SHOULD honor this request for hierarchical expansions as well, and simply return the overall count',
        )]
        public readonly ?int $count = null,
        #[FhirOperationParameter(
            name: 'includeDesignations',
            phpName: 'includeDesignations',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'Controls whether concept designations are to be included or excluded in value set expansions',
        )]
        public readonly ?bool $includeDesignations = null,
        #[FhirOperationParameter(
            name: 'designation',
            phpName: 'designation',
            use: 'in',
            min: 0,
            max: '*',
            type: 'string',
            documentation: 'A [token](search.html#token) that specifies a system+code that is either a use or a language. Designations that match by language or use are included in the expansion. If no designation is specified, it is at the server discretion which designations to return',
        )]
        public readonly array $designation = [],
        #[FhirOperationParameter(
            name: 'includeDefinition',
            phpName: 'includeDefinition',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'Controls whether the value set definition is included or excluded in value set expansions',
        )]
        public readonly ?bool $includeDefinition = null,
        #[FhirOperationParameter(
            name: 'activeOnly',
            phpName: 'activeOnly',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'Controls whether inactive concepts are included or excluded in value set expansions. Note that if the value set explicitly specifies that inactive codes are included, this parameter can still remove them from a specific expansion, but this parameter cannot include them if the value set excludes them',
        )]
        public readonly ?bool $activeOnly = null,
        #[FhirOperationParameter(
            name: 'excludeNested',
            phpName: 'excludeNested',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'Controls whether or not the value set expansion nests codes or not (i.e. ValueSet.expansion.contains.contains)',
        )]
        public readonly ?bool $excludeNested = null,
        #[FhirOperationParameter(
            name: 'excludeNotForUI',
            phpName: 'excludeNotForUI',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'Controls whether or not the value set expansion is assembled for a user interface use or not. Value sets intended for User Interface might include [\'abstract\' codes](codesystem.html#status) or have nested contains with items with no code or abstract = true, with the sole purpose of helping a user navigate through the list efficiently, where as a value set not generated for UI use might be flat, and only contain the selectable codes in the value set. The exact implications of \'for UI\' depend on the code system, and what properties it exposes for a terminology server to use. In the FHIR Specification itself, the value set expansions are generated with excludeNotForUI = false, and the expansions used when generated schema / code etc, or performing validation, are all excludeNotForUI = true.',
        )]
        public readonly ?bool $excludeNotForUI = null,
        #[FhirOperationParameter(
            name: 'excludePostCoordinated',
            phpName: 'excludePostCoordinated',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
            documentation: 'Controls whether or not the value set expansion includes post coordinated codes',
        )]
        public readonly ?bool $excludePostCoordinated = null,
        #[FhirOperationParameter(
            name: 'displayLanguage',
            phpName: 'displayLanguage',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'Specifies the language to be used for description in the expansions i.e. the language to be used for ValueSet.expansion.contains.display',
        )]
        public readonly ?string $displayLanguage = null,
        #[FhirOperationParameter(
            name: 'exclude-system',
            phpName: 'excludeSystem',
            use: 'in',
            min: 0,
            max: '*',
            type: 'canonical',
            documentation: 'Code system, or a particular version of a code system to be excluded from the value set expansion. The format is the same as a canonical URL: [system]|[version] - e.g. http://loinc.org|2.56',
        )]
        public readonly array $excludeSystem = [],
        #[FhirOperationParameter(
            name: 'system-version',
            phpName: 'systemVersion',
            use: 'in',
            min: 0,
            max: '*',
            type: 'canonical',
            documentation: 'Specifies a version to use for a system, if the value set does not specify which one to use. The format is the same as a canonical URL: [system]|[version] - e.g. http://loinc.org|2.56',
        )]
        public readonly array $systemVersion = [],
        #[FhirOperationParameter(
            name: 'check-system-version',
            phpName: 'checkSystemVersion',
            use: 'in',
            min: 0,
            max: '*',
            type: 'canonical',
            documentation: 'Edge Case: Specifies a version to use for a system. If a value set specifies a different version, an error is returned instead of the expansion. The format is the same as a canonical URL: [system]|[version] - e.g. http://loinc.org|2.56',
        )]
        public readonly array $checkSystemVersion = [],
        #[FhirOperationParameter(
            name: 'force-system-version',
            phpName: 'forceSystemVersion',
            use: 'in',
            min: 0,
            max: '*',
            type: 'canonical',
            documentation: 'Edge Case: Specifies a version to use for a system. This parameter overrides any specified version in the value set (and any it depends on). The format is the same as a canonical URL: [system]|[version] - e.g. http://loinc.org|2.56. Note that this has obvious safety issues, in that it may result in a value set expansion giving a different list of codes that is both wrong and unsafe, and implementers should only use this capability reluctantly. It primarily exists to deal with situations where specifications have fallen into decay as time passes. If the value is override, the version used SHALL explicitly be represented in the expansion parameters',
        )]
        public readonly array $forceSystemVersion = [],
    ) {
    }
}
