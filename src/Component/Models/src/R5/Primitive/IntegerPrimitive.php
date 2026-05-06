<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Primitive;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\PrimitiveType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/integer
 *
 * @description A whole number
 */
#[FHIRPrimitive(primitiveType: 'integer', fhirVersion: 'R5')]
class IntegerPrimitive extends PrimitiveType
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var int|null value Primitive value for integer */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.Integer', propertyKind: 'scalar', xmlSerializedName: '@value')]
        public ?int $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}
