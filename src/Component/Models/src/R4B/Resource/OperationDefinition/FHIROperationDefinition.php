<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/OperationDefinition
 *
 * @description A formal computable definition of an operation (on the RESTful interface) or a named query (using the search interaction).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'OperationDefinition',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/OperationDefinition',
    fhirVersion: 'R4B',
)]
class FHIROperationDefinition extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this operation definition, represented as a URI (globally unique) */
        public ?\FHIRUri $url = null,
        /** @var FHIRString|string|null version Business version of the operation definition */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this operation definition (computer friendly) */
        #[NotBlank]
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this operation definition (human friendly) */
        public \FHIRString|string|null $title = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIROperationKindType|null kind operation | query */
        #[NotBlank]
        public ?\FHIROperationKindType $kind = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?\FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the operation definition */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for operation definition (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this operation definition is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRBoolean|null affectsState Whether content is changed by the operation */
        public ?\FHIRBoolean $affectsState = null,
        /** @var FHIRCode|null code Name used to invoke the operation */
        #[NotBlank]
        public ?\FHIRCode $code = null,
        /** @var FHIRMarkdown|null comment Additional information about use */
        public ?\FHIRMarkdown $comment = null,
        /** @var FHIRCanonical|null base Marks this as a profile of the base */
        public ?\FHIRCanonical $base = null,
        /** @var array<FHIRResourceTypeType> resource Types this operation applies to */
        public array $resource = [],
        /** @var FHIRBoolean|null system Invoke at the system level? */
        #[NotBlank]
        public ?\FHIRBoolean $system = null,
        /** @var FHIRBoolean|null type Invoke at the type level? */
        #[NotBlank]
        public ?\FHIRBoolean $type = null,
        /** @var FHIRBoolean|null instance Invoke on an instance? */
        #[NotBlank]
        public ?\FHIRBoolean $instance = null,
        /** @var FHIRCanonical|null inputProfile Validation information for in parameters */
        public ?\FHIRCanonical $inputProfile = null,
        /** @var FHIRCanonical|null outputProfile Validation information for out parameters */
        public ?\FHIRCanonical $outputProfile = null,
        /** @var array<FHIROperationDefinitionParameter> parameter Parameters for the operation/query */
        public array $parameter = [],
        /** @var array<FHIROperationDefinitionOverload> overload Define overloaded variants for when  generating code */
        public array $overload = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
