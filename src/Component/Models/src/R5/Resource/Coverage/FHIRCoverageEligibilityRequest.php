<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIREligibilityRequestPurposeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CoverageEligibilityRequest
 *
 * @description The CoverageEligibilityRequest provides patient and insurance coverage information to an insurer for them to respond, in the form of an CoverageEligibilityResponse, with information regarding whether the stated coverage is valid and in-force and optionally to provide the insurance details of the policy.
 */
#[FhirResource(
    type: 'CoverageEligibilityRequest',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/CoverageEligibilityRequest',
    fhirVersion: 'R5',
)]
class FHIRCoverageEligibilityRequest extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business Identifier for coverage eligiblity request */
        public array $identifier = [],
        /** @var FHIRFinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FHIRFinancialResourceStatusCodesType $status = null,
        /** @var FHIRCodeableConcept|null priority Desired processing priority */
        public ?FHIRCodeableConcept $priority = null,
        /** @var array<FHIREligibilityRequestPurposeType> purpose auth-requirements | benefits | discovery | validation */
        public array $purpose = [],
        /** @var FHIRReference|null patient Intended recipient of products and services */
        #[NotBlank]
        public ?FHIRReference $patient = null,
        /** @var array<FHIRCoverageEligibilityRequestEvent> event Event information */
        public array $event = [],
        /** @var FHIRDate|FHIRPeriod|null servicedX Estimated date or dates of service */
        public FHIRDate|FHIRPeriod|null $servicedX = null,
        /** @var FHIRDateTime|null created Creation date */
        #[NotBlank]
        public ?FHIRDateTime $created = null,
        /** @var FHIRReference|null enterer Author */
        public ?FHIRReference $enterer = null,
        /** @var FHIRReference|null provider Party responsible for the request */
        public ?FHIRReference $provider = null,
        /** @var FHIRReference|null insurer Coverage issuer */
        #[NotBlank]
        public ?FHIRReference $insurer = null,
        /** @var FHIRReference|null facility Servicing facility */
        public ?FHIRReference $facility = null,
        /** @var array<FHIRCoverageEligibilityRequestSupportingInfo> supportingInfo Supporting information */
        public array $supportingInfo = [],
        /** @var array<FHIRCoverageEligibilityRequestInsurance> insurance Patient insurance information */
        public array $insurance = [],
        /** @var array<FHIRCoverageEligibilityRequestItem> item Item to be evaluated for eligibiity */
        public array $item = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
