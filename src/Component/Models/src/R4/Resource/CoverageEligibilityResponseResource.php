<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EligibilityResponsePurposeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityResponse\CoverageEligibilityResponseError;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityResponse\CoverageEligibilityResponseInsurance;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/CoverageEligibilityResponse',
    fhirVersion: 'R4',
)]
class CoverageEligibilityResponseResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Business Identifier for coverage eligiblity request */
        public array $identifier = [],
        /** @var FinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FinancialResourceStatusCodesType $status = null,
        /** @var array<EligibilityResponsePurposeType> purpose auth-requirements | benefits | discovery | validation */
        public array $purpose = [],
        /** @var Reference|null patient Intended recipient of products and services */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var DatePrimitive|Period|null servicedX Estimated date or dates of service */
        public DatePrimitive|Period|null $servicedX = null,
        /** @var DateTimePrimitive|null created Response creation date */
        #[NotBlank]
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null requestor Party responsible for the request */
        public ?Reference $requestor = null,
        /** @var Reference|null request Eligibility request reference */
        #[NotBlank]
        public ?Reference $request = null,
        /** @var ClaimProcessingCodesType|null outcome queued | complete | error | partial */
        #[NotBlank]
        public ?ClaimProcessingCodesType $outcome = null,
        /** @var StringPrimitive|string|null disposition Disposition Message */
        public StringPrimitive|string|null $disposition = null,
        /** @var Reference|null insurer Coverage issuer */
        #[NotBlank]
        public ?Reference $insurer = null,
        /** @var array<CoverageEligibilityResponseInsurance> insurance Patient insurance information */
        public array $insurance = [],
        /** @var StringPrimitive|string|null preAuthRef Preauthorization reference */
        public StringPrimitive|string|null $preAuthRef = null,
        /** @var CodeableConcept|null form Printed form identifier */
        public ?CodeableConcept $form = null,
        /** @var array<CoverageEligibilityResponseError> error Processing errors */
        public array $error = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
