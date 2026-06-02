<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Distance
 *
 * @description A length - a value with a unit that is a physical distance.
 */
#[FHIRComplexType(typeName: 'Distance', fhirVersion: 'R4')]
#[FHIRPathInvariant(
    key: 'dis-1',
    severity: 'error',
    expression: '(code.exists() or value.empty()) and (system.empty() or system = %ucum)',
    human: 'There SHALL be a code if there is a value and it SHALL be an expression of length.  If system is present, it SHALL be UCUM.',
)]
class Distance extends Quantity
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var numeric-string|null value Numerical value (with implicit precision) */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $value = null,
        /** @var QuantityComparatorType|null comparator < | <= | >= | > - how to understand the value */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/quantity-comparator|4.0.1', strength: 'required'), FHIRIsModifier(reason: 'This is labeled as "Is Modifier" because the comparator modifies the interpretation of the value significantly. If there is no comparator, then there is no modification of the value')]
        public ?QuantityComparatorType $comparator = null,
        /** @var StringPrimitive|string|null unit Unit representation */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $unit = null,
        /** @var UriPrimitive|null system System that defines coded unit form */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $system = null,
        /** @var CodePrimitive|null code Coded form of the unit */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $code = null,
    ) {
        parent::__construct($id, $extension, $value, $comparator, $unit, $system, $code);
    }
}
