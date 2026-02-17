<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan\InsurancePlanContact;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan\InsurancePlanCoverage;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\InsurancePlan\InsurancePlanPlan;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/InsurancePlan
 *
 * @description Details of a Health Insurance product/plan provided by an organization.
 */
#[FhirResource(type: 'InsurancePlan', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/InsurancePlan', fhirVersion: 'R4')]
class InsurancePlanResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for Product */
        public array $identifier = [],
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        public ?PublicationStatusType $status = null,
        /** @var array<CodeableConcept> type Kind of product */
        public array $type = [],
        /** @var StringPrimitive|string|null name Official name */
        public StringPrimitive|string|null $name = null,
        /** @var array<StringPrimitive|string> alias Alternate names */
        public array $alias = [],
        /** @var Period|null period When the product is available */
        public ?Period $period = null,
        /** @var Reference|null ownedBy Plan issuer */
        public ?Reference $ownedBy = null,
        /** @var Reference|null administeredBy Product administrator */
        public ?Reference $administeredBy = null,
        /** @var array<Reference> coverageArea Where product applies */
        public array $coverageArea = [],
        /** @var array<InsurancePlanContact> contact Contact for the product */
        public array $contact = [],
        /** @var array<Reference> endpoint Technical endpoint */
        public array $endpoint = [],
        /** @var array<Reference> network What networks are Included */
        public array $network = [],
        /** @var array<InsurancePlanCoverage> coverage Coverage details */
        public array $coverage = [],
        /** @var array<InsurancePlanPlan> plan Plan details */
        public array $plan = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
