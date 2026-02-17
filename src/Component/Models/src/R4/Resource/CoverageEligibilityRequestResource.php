<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\EligibilityRequestPurposeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityRequest\CoverageEligibilityRequestInsurance;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityRequest\CoverageEligibilityRequestItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CoverageEligibilityRequest\CoverageEligibilityRequestSupportingInfo;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/CoverageEligibilityRequest',
    fhirVersion: 'R4',
)]
class CoverageEligibilityRequestResource extends DomainResourceResource
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
        /** @var CodeableConcept|null priority Desired processing priority */
        public ?CodeableConcept $priority = null,
        /** @var array<EligibilityRequestPurposeType> purpose auth-requirements | benefits | discovery | validation */
        public array $purpose = [],
        /** @var Reference|null patient Intended recipient of products and services */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var DatePrimitive|Period|null servicedX Estimated date or dates of service */
        public DatePrimitive|Period|null $servicedX = null,
        /** @var DateTimePrimitive|null created Creation date */
        #[NotBlank]
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null enterer Author */
        public ?Reference $enterer = null,
        /** @var Reference|null provider Party responsible for the request */
        public ?Reference $provider = null,
        /** @var Reference|null insurer Coverage issuer */
        #[NotBlank]
        public ?Reference $insurer = null,
        /** @var Reference|null facility Servicing facility */
        public ?Reference $facility = null,
        /** @var array<CoverageEligibilityRequestSupportingInfo> supportingInfo Supporting information */
        public array $supportingInfo = [],
        /** @var array<CoverageEligibilityRequestInsurance> insurance Patient insurance information */
        public array $insurance = [],
        /** @var array<CoverageEligibilityRequestItem> item Item to be evaluated for eligibiity */
        public array $item = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
