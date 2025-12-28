<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ObservationDefinition
 *
 * @description Set of definitional characteristics for a kind of observation or measurement produced or consumed by an orderable health care service.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'ObservationDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ObservationDefinition',
    fhirVersion: 'R5',
)]
class FHIRObservationDefinition extends FHIRDomainResource
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
        /** @var FHIRUri|null url Logical canonical URL to reference this ObservationDefinition (globally unique) */
        public ?\FHIRUri $url = null,
        /** @var FHIRIdentifier|null identifier Business identifier of the ObservationDefinition */
        public ?\FHIRIdentifier $identifier = null,
        /** @var FHIRString|string|null version Business version of the ObservationDefinition */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public \FHIRString|string|\FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this ObservationDefinition (computer friendly) */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this ObservationDefinition (human friendly) */
        public \FHIRString|string|null $title = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental If for testing purposes, not real usage */
        public ?\FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher The name of the individual or organization that published the ObservationDefinition */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the ObservationDefinition */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext Content intends to support these contexts */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for this ObservationDefinition (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this ObservationDefinition is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public \FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRDate|null approvalDate When ObservationDefinition was approved by publisher */
        public ?\FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate Date on which the asset content was last reviewed by the publisher */
        public ?\FHIRDate $lastReviewDate = null,
        /** @var FHIRPeriod|null effectivePeriod The effective date range for the ObservationDefinition */
        public ?\FHIRPeriod $effectivePeriod = null,
        /** @var array<FHIRCanonical> derivedFromCanonical Based on FHIR definition of another observation */
        public array $derivedFromCanonical = [],
        /** @var array<FHIRUri> derivedFromUri Based on external definition */
        public array $derivedFromUri = [],
        /** @var array<FHIRCodeableConcept> subject Type of subject for the defined observation */
        public array $subject = [],
        /** @var FHIRCodeableConcept|null performerType Desired kind of performer for such kind of observation */
        public ?\FHIRCodeableConcept $performerType = null,
        /** @var array<FHIRCodeableConcept> category General type of observation */
        public array $category = [],
        /** @var FHIRCodeableConcept|null code Type of observation */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var array<FHIRObservationDataTypeType> permittedDataType Quantity | CodeableConcept | string | boolean | integer | Range | Ratio | SampledData | time | dateTime | Period */
        public array $permittedDataType = [],
        /** @var FHIRBoolean|null multipleResultsAllowed Multiple results allowed for conforming observations */
        public ?\FHIRBoolean $multipleResultsAllowed = null,
        /** @var FHIRCodeableConcept|null bodySite Body part to be observed */
        public ?\FHIRCodeableConcept $bodySite = null,
        /** @var FHIRCodeableConcept|null method Method used to produce the observation */
        public ?\FHIRCodeableConcept $method = null,
        /** @var array<FHIRReference> specimen Kind of specimen used by this type of observation */
        public array $specimen = [],
        /** @var array<FHIRReference> device Measurement device or model of device */
        public array $device = [],
        /** @var FHIRString|string|null preferredReportName The preferred name to be used when reporting the observation results */
        public \FHIRString|string|null $preferredReportName = null,
        /** @var array<FHIRCoding> permittedUnit Unit for quantitative results */
        public array $permittedUnit = [],
        /** @var array<FHIRObservationDefinitionQualifiedValue> qualifiedValue Set of qualified values for observation results */
        public array $qualifiedValue = [],
        /** @var array<FHIRReference> hasMember Definitions of related resources belonging to this kind of observation group */
        public array $hasMember = [],
        /** @var array<FHIRObservationDefinitionComponent> component Component results */
        public array $component = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
