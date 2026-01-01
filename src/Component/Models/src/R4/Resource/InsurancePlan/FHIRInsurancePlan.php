<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/InsurancePlan
 *
 * @description Details of a Health Insurance product/plan provided by an organization.
 */
#[FhirResource(type: 'InsurancePlan', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/InsurancePlan', fhirVersion: 'R4')]
class FHIRInsurancePlan extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business Identifier for Product */
        public array $identifier = [],
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        public ?FHIRPublicationStatusType $status = null,
        /** @var array<FHIRCodeableConcept> type Kind of product */
        public array $type = [],
        /** @var FHIRString|string|null name Official name */
        public FHIRString|string|null $name = null,
        /** @var array<FHIRString|string> alias Alternate names */
        public array $alias = [],
        /** @var FHIRPeriod|null period When the product is available */
        public ?FHIRPeriod $period = null,
        /** @var FHIRReference|null ownedBy Plan issuer */
        public ?FHIRReference $ownedBy = null,
        /** @var FHIRReference|null administeredBy Product administrator */
        public ?FHIRReference $administeredBy = null,
        /** @var array<FHIRReference> coverageArea Where product applies */
        public array $coverageArea = [],
        /** @var array<FHIRInsurancePlanContact> contact Contact for the product */
        public array $contact = [],
        /** @var array<FHIRReference> endpoint Technical endpoint */
        public array $endpoint = [],
        /** @var array<FHIRReference> network What networks are Included */
        public array $network = [],
        /** @var array<FHIRInsurancePlanCoverage> coverage Coverage details */
        public array $coverage = [],
        /** @var array<FHIRInsurancePlanPlan> plan Plan details */
        public array $plan = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
