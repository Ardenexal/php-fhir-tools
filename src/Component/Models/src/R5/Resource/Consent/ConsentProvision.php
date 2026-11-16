<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Consent;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @description An exception to the base policy of this consent. An exception can be an addition or removal of access permissions.
 */
#[FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.provision', fhirVersion: 'R5')]
class ConsentProvision extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var Period|null period Timeframe for this provision */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex'), Valid]
        public ?Period $period = null,
        /** @var array<ConsentProvisionActor> actor Who|what controlled by this provision (or group, by role) */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Consent\ConsentProvisionActor',
        )]
        #[Valid]
        public array $actor = [],
        /** @var array<CodeableConcept> action Actions controlled by this provision */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        #[Valid]
        public array $action = [],
        /** @var array<Coding> securityLabel Security Labels that define affected resources */
        #[FhirProperty(
            fhirType: 'Coding',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
        )]
        #[Valid]
        public array $securityLabel = [],
        /** @var array<Coding> purpose Context of activities covered by this provision */
        #[FhirProperty(
            fhirType: 'Coding',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
        )]
        #[Valid]
        #[FHIRValueSetBinding(
            valueSetUrl: 'http://terminology.hl7.org/ValueSet/v3-PurposeOfUse',
            strength: 'extensible',
            enumClass: 'Ardenexal\FHIRTools\Component\Models\R5\Enum\PurposeOfUse',
        )]
        public array $purpose = [],
        /** @var array<Coding> documentType e.g. Resource Type, Profile, CDA, etc */
        #[FhirProperty(
            fhirType: 'Coding',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
        )]
        #[Valid]
        #[FHIRValueSetBinding(
            valueSetUrl: 'http://hl7.org/fhir/ValueSet/consent-content-class',
            strength: 'preferred',
            enumClass: 'Ardenexal\FHIRTools\Component\Models\R5\Enum\ConsentContentClass',
        )]
        public array $documentType = [],
        /** @var array<Coding> resourceType e.g. Resource Type, Profile, etc */
        #[FhirProperty(
            fhirType: 'Coding',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
        )]
        #[Valid]
        #[FHIRValueSetBinding(
            valueSetUrl: 'http://hl7.org/fhir/ValueSet/resource-types',
            strength: 'extensible',
            enumClass: 'Ardenexal\FHIRTools\Component\Models\R5\Enum\ResourceType',
        )]
        public array $resourceType = [],
        /** @var array<CodeableConcept> code e.g. LOINC or SNOMED CT code, etc. in the content */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        #[Valid]
        public array $code = [],
        /** @var Period|null dataPeriod Timeframe for data controlled by this provision */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex'), Valid]
        public ?Period $dataPeriod = null,
        /** @var array<ConsentProvisionData> data Data controlled by this provision */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Consent\ConsentProvisionData',
        )]
        #[Valid]
        public array $data = [],
        /** @var Expression|null expression A computable expression of the consent */
        #[FhirProperty(fhirType: 'Expression', propertyKind: 'complex'), Valid]
        public ?Expression $expression = null,
        /** @var array<ConsentProvision> provision Nested Exception Provisions */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Consent\ConsentProvision',
        )]
        #[Valid]
        public array $provision = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
