<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Primitive;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Element;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/date
 *
 * @description A date or partial date (e.g. just year or year + month). There is no time zone. The format is a union of the schema types gYear, gYearMonth and date.  Dates SHALL be valid dates.
 */
#[FHIRPrimitive(primitiveType: 'date', fhirVersion: 'R4B')]
class DatePrimitive extends Element
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var FHIRDate|null value Primitive value for date */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.Date', propertyKind: 'scalar', xmlSerializedName: '@value')]
        public ?FHIRDate $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}
