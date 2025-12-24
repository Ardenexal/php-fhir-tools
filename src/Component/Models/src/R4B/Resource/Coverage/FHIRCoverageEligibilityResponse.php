<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/CoverageEligibilityResponse
 *
 * @description This resource provides eligibility and plan details from the processing of an CoverageEligibilityRequest resource.
 */
#[FhirResource(
    type: 'CoverageEligibilityResponse',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/CoverageEligibilityResponse',
    fhirVersion: 'R4B',
)]
class FHIRCoverageEligibilityResponse extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
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
        /** @var array<FHIREligibilityResponsePurposeType> purpose auth-requirements | benefits | discovery | validation */
        public array $purpose = [],
        /** @var FHIRReference|null patient Intended recipient of products and services */
        #[NotBlank]
        public ?FHIRReference $patient = null,
        /** @var FHIRDate|FHIRPeriod|null servicedX Estimated date or dates of service */
        public FHIRDate|FHIRPeriod|null $servicedX = null,
        /** @var FHIRDateTime|null created Response creation date */
        #[NotBlank]
        public ?FHIRDateTime $created = null,
        /** @var FHIRReference|null requestor Party responsible for the request */
        public ?FHIRReference $requestor = null,
        /** @var FHIRReference|null request Eligibility request reference */
        #[NotBlank]
        public ?FHIRReference $request = null,
        /** @var FHIRRemittanceOutcomeType|null outcome queued | complete | error | partial */
        #[NotBlank]
        public ?FHIRRemittanceOutcomeType $outcome = null,
        /** @var FHIRString|string|null disposition Disposition Message */
        public FHIRString|string|null $disposition = null,
        /** @var FHIRReference|null insurer Coverage issuer */
        #[NotBlank]
        public ?FHIRReference $insurer = null,
        /** @var array<FHIRCoverageEligibilityResponseInsurance> insurance Patient insurance information */
        public array $insurance = [],
        /** @var FHIRString|string|null preAuthRef Preauthorization reference */
        public FHIRString|string|null $preAuthRef = null,
        /** @var FHIRCodeableConcept|null form Printed form identifier */
        public ?FHIRCodeableConcept $form = null,
        /** @var array<FHIRCoverageEligibilityResponseError> error Processing errors */
        public array $error = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
