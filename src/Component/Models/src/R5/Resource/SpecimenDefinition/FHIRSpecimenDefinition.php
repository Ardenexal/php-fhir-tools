<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SpecimenDefinition
 *
 * @description A kind of specimen with associated set of requirements.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'SpecimenDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/SpecimenDefinition',
    fhirVersion: 'R5',
)]
class FHIRSpecimenDefinition extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Logical canonical URL to reference this SpecimenDefinition (globally unique) */
        public ?\FHIRUri $url = null,
        /** @var FHIRIdentifier|null identifier Business identifier */
        public ?\FHIRIdentifier $identifier = null,
        /** @var FHIRString|string|null version Business version of the SpecimenDefinition */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public \FHIRString|string|\FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this {{title}} (computer friendly) */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this SpecimenDefinition (Human friendly) */
        public \FHIRString|string|null $title = null,
        /** @var array<FHIRCanonical> derivedFromCanonical Based on FHIR definition of another SpecimenDefinition */
        public array $derivedFromCanonical = [],
        /** @var array<FHIRUri> derivedFromUri Based on external definition */
        public array $derivedFromUri = [],
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental If this SpecimenDefinition is not for real usage */
        public ?\FHIRBoolean $experimental = null,
        /** @var FHIRCodeableConcept|FHIRReference|null subjectX Type of subject for specimen collection */
        public \FHIRCodeableConcept|\FHIRReference|null $subjectX = null,
        /** @var FHIRDateTime|null date Date status first applied */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher The name of the individual or organization that published the SpecimenDefinition */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the SpecimenDefinition */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext Content intends to support these contexts */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for this SpecimenDefinition (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this SpecimenDefinition is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public \FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRDate|null approvalDate When SpecimenDefinition was approved by publisher */
        public ?\FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate The date on which the asset content was last reviewed by the publisher */
        public ?\FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod The effective date range for the SpecimenDefinition */
        public ?\FHIRPeriod $effectivePeriod = null,
        /** @var FHIRCodeableConcept|null typeCollected Kind of material to collect */
        public ?\FHIRCodeableConcept $typeCollected = null,
        /** @var array<FHIRCodeableConcept> patientPreparation Patient preparation for collection */
        public array $patientPreparation = [],
        /** @var FHIRString|string|null timeAspect Time aspect for collection */
        public \FHIRString|string|null $timeAspect = null,
        /** @var array<FHIRCodeableConcept> collection Specimen collection procedure */
        public array $collection = [],
        /** @var array<FHIRSpecimenDefinitionTypeTested> typeTested Specimen in container intended for testing by lab */
        public array $typeTested = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
