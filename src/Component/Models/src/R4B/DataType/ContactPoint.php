<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ContactPoint
 *
 * @description Details for all kinds of technology mediated contact points for a person or organization, including telephone, email, etc.
 */
#[FHIRComplexType(typeName: 'ContactPoint', fhirVersion: 'R4B')]
#[FHIRPathInvariant(
    key: 'cpt-2',
    severity: 'error',
    expression: 'value.empty() or system.exists()',
    human: 'A system is required if a value is provided.',
)]
class ContactPoint extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var ContactPointSystemType|null system phone | fax | email | pager | url | sms | other */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/contact-point-system|4.3.0', strength: 'required')]
        public ?ContactPointSystemType $system = null,
        /** @var StringPrimitive|string|null value The actual contact point details */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $value = null,
        /** @var ContactPointUseType|null use home | work | temp | old | mobile - purpose of this contact point */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/contact-point-use|4.3.0', strength: 'required'), FHIRIsModifier(reason: 'This is labeled as "Is Modifier" because applications should not mistake a temporary or old contact etc.for a current/permanent one')]
        public ?ContactPointUseType $use = null,
        /** @var PositiveIntPrimitive|null rank Specify preferred order of use (1 = highest) */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive')]
        public ?PositiveIntPrimitive $rank = null,
        /** @var Period|null period Time period when the contact point was/is in use */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension);
    }
}
