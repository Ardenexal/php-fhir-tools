<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Duration
 *
 * @description A length of time.
 */
#[FHIRComplexType(typeName: 'Duration', fhirVersion: 'R4')]
class Duration extends Quantity
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
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
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
