<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/GraphDefinition
 *
 * @description A formal computable definition of a graph of resources - that is, a coherent set of resources that form a graph by following references. The Graph Definition resource defines a set and makes rules about the set.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'GraphDefinition',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/GraphDefinition',
    fhirVersion: 'R4',
)]
class FHIRGraphDefinition extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this graph definition, represented as a URI (globally unique) */
        public ?\FHIRUri $url = null,
        /** @var FHIRString|string|null version Business version of the graph definition */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this graph definition (computer friendly) */
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
        /** @var FHIRMarkdown|null description Natural language description of the graph definition */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for graph definition (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this graph definition is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRResourceTypeType|null start Type of resource at which the graph starts */
        #[NotBlank]
        public ?\FHIRResourceTypeType $start = null,
        /** @var FHIRCanonical|null profile Profile on base resource */
        public ?\FHIRCanonical $profile = null,
        /** @var array<FHIRGraphDefinitionLink> link Links this graph makes rules about */
        public array $link = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
