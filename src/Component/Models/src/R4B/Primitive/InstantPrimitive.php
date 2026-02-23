<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Primitive;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Element;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/instant
 *
 * @description An instant in time - known at least to the second
 */
#[FHIRPrimitive(primitiveType: 'instant', fhirVersion: 'R4B')]
class InstantPrimitive extends Element
{
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.String',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'value' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.DateTime',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var \DateTimeInterface|null value Primitive value for instant */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.DateTime', propertyKind: 'scalar')]
        public ?\DateTimeInterface $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}
