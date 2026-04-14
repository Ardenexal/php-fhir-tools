<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\InsurancePlan\InsurancePlanCoverage;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\InsurancePlan\InsurancePlanPlan;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/InsurancePlan
 *
 * @description Details of a Health Insurance product/plan provided by an organization.
 */
#[FhirResource(type: 'InsurancePlan', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/InsurancePlan', fhirVersion: 'R5')]
class InsurancePlanResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Business Identifier for Product */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?PublicationStatusType $status = null,
        /** @var array<CodeableConcept> type Kind of product */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $type = [],
        /** @var StringPrimitive|string|null name Official name */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $name = null,
        /** @var array<StringPrimitive|string> alias Alternate names */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $alias = [],
        /** @var Period|null period When the product is available */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
        /** @var Reference|null ownedBy Product issuer */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $ownedBy = null,
        /** @var Reference|null administeredBy Product administrator */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $administeredBy = null,
        /** @var array<Reference> coverageArea Where product applies */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $coverageArea = [],
        /** @var array<ExtendedContactDetail> contact Official contact details relevant to the health insurance plan/product */
        #[FhirProperty(
            fhirType: 'ExtendedContactDetail',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail',
        )]
        public array $contact = [],
        /** @var array<Reference> endpoint Technical endpoint */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $endpoint = [],
        /** @var array<Reference> network What networks are Included */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $network = [],
        /** @var array<InsurancePlanCoverage> coverage Coverage details */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\InsurancePlan\InsurancePlanCoverage',
        )]
        public array $coverage = [],
        /** @var array<InsurancePlanPlan> plan Plan details */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\InsurancePlan\InsurancePlanPlan',
        )]
        public array $plan = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
