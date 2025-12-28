<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductAuthorization
 *
 * @description The regulatory authorization of a medicinal product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'MedicinalProductAuthorization',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductAuthorization',
    fhirVersion: 'R4B',
)]
class FHIRMedicinalProductAuthorization extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business identifier for the marketing authorization, as assigned by a regulator */
        public array $identifier = [],
        /** @var FHIRReference|null subject The medicinal product that is being authorized */
        public ?\FHIRReference $subject = null,
        /** @var array<FHIRCodeableConcept> country The country in which the marketing authorization has been granted */
        public array $country = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Jurisdiction within a country */
        public array $jurisdiction = [],
        /** @var FHIRCodeableConcept|null status The status of the marketing authorization */
        public ?\FHIRCodeableConcept $status = null,
        /** @var FHIRDateTime|null statusDate The date at which the given status has become applicable */
        public ?\FHIRDateTime $statusDate = null,
        /** @var FHIRDateTime|null restoreDate The date when a suspended the marketing or the marketing authorization of the product is anticipated to be restored */
        public ?\FHIRDateTime $restoreDate = null,
        /** @var FHIRPeriod|null validityPeriod The beginning of the time period in which the marketing authorization is in the specific status shall be specified A complete date consisting of day, month and year shall be specified using the ISO 8601 date format */
        public ?\FHIRPeriod $validityPeriod = null,
        /** @var FHIRPeriod|null dataExclusivityPeriod A period of time after authorization before generic product applicatiosn can be submitted */
        public ?\FHIRPeriod $dataExclusivityPeriod = null,
        /** @var FHIRDateTime|null dateOfFirstAuthorization The date when the first authorization was granted by a Medicines Regulatory Agency */
        public ?\FHIRDateTime $dateOfFirstAuthorization = null,
        /** @var FHIRDateTime|null internationalBirthDate Date of first marketing authorization for a company's new medicinal product in any country in the World */
        public ?\FHIRDateTime $internationalBirthDate = null,
        /** @var FHIRCodeableConcept|null legalBasis The legal framework against which this authorization is granted */
        public ?\FHIRCodeableConcept $legalBasis = null,
        /** @var array<FHIRMedicinalProductAuthorizationJurisdictionalAuthorization> jurisdictionalAuthorization Authorization in areas within a country */
        public array $jurisdictionalAuthorization = [],
        /** @var FHIRReference|null holder Marketing Authorization Holder */
        public ?\FHIRReference $holder = null,
        /** @var FHIRReference|null regulator Medicines Regulatory Agency */
        public ?\FHIRReference $regulator = null,
        /** @var FHIRMedicinalProductAuthorizationProcedure|null procedure The regulatory procedure for granting or amending a marketing authorization */
        public ?\FHIRMedicinalProductAuthorizationProcedure $procedure = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
