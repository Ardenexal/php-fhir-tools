<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Primitive;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Element;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/time
 *
 * @description A time during the day, with no date specified
 */
#[FHIRPrimitive(primitiveType: 'time', fhirVersion: 'R4')]
class TimePrimitive extends Element implements \Stringable
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
        /** @var FHIRTime|null value Primitive value for time */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.Time', propertyKind: 'scalar', xmlSerializedName: '@value')]
        public ?FHIRTime $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}
