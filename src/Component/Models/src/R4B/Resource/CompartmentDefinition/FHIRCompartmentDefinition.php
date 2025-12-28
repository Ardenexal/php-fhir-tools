<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CompartmentDefinition
 *
 * @description A compartment definition that defines how resources are accessed on a server.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'CompartmentDefinition',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/CompartmentDefinition',
    fhirVersion: 'R4B',
)]
class FHIRCompartmentDefinition extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this compartment definition, represented as a URI (globally unique) */
        #[NotBlank]
        public ?\FHIRUri $url = null,
        /** @var FHIRString|string|null version Business version of the compartment definition */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this compartment definition (computer friendly) */
        #[NotBlank]
        public \FHIRString|string|null $name = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?\FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the compartment definition */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var FHIRMarkdown|null purpose Why this compartment definition is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRCompartmentTypeType|null code Patient | Encounter | RelatedPerson | Practitioner | Device */
        #[NotBlank]
        public ?\FHIRCompartmentTypeType $code = null,
        /** @var FHIRBoolean|null search Whether the search syntax is supported */
        #[NotBlank]
        public ?\FHIRBoolean $search = null,
        /** @var array<FHIRCompartmentDefinitionResource> resource How a resource is related to the compartment */
        public array $resource = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
