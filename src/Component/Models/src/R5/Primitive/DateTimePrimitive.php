<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Primitive;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\PrimitiveType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/dateTime
 *
 * @description A date, date-time or partial date (e.g. just year or year + month).  If hours and minutes are specified, a UTC offset SHALL be populated. The format is a union of the schema types gYear, gYearMonth, date and dateTime. Seconds must be provided due to schema type constraints but may be zero-filled and may be ignored.                 Dates SHALL be valid dates.
 */
#[FHIRPrimitive(primitiveType: 'dateTime', fhirVersion: 'R5')]
class DateTimePrimitive extends PrimitiveType implements \Stringable
{
    public function __toString(): string
    {
        return $this->value === null ? '' : (string) $this->value;
    }

    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var FHIRDateTime|null value Primitive value for dateTime */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.DateTime', propertyKind: 'scalar', xmlSerializedName: '@value')]
        public ?FHIRDateTime $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}
