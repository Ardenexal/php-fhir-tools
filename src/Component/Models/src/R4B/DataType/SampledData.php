<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/SampledData
 *
 * @description A series of measurements taken by a device, with upper and lower limits. There may be more than one dimension in the data.
 */
#[FHIRComplexType(typeName: 'SampledData', fhirVersion: 'R4B')]
class SampledData extends Element
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
        'origin' => [
            'fhirType'     => 'Quantity',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'period' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'factor' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'lowerLimit' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'upperLimit' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'dimensions' => [
            'fhirType'     => 'positiveInt',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'data' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var Quantity|null origin Zero value and units */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Quantity $origin = null,
        /** @var float|null period Number of milliseconds between samples */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?float $period = null,
        /** @var float|null factor Multiply data by this before adding to origin */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?float $factor = null,
        /** @var float|null lowerLimit Lower limit of detection */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?float $lowerLimit = null,
        /** @var float|null upperLimit Upper limit of detection */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?float $upperLimit = null,
        /** @var PositiveIntPrimitive|null dimensions Number of sample points at each time point */
        #[FhirProperty(fhirType: 'positiveInt', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?PositiveIntPrimitive $dimensions = null,
        /** @var StringPrimitive|string|null data Decimal values with spaces, or "E" | "U" | "L" */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $data = null,
    ) {
        parent::__construct($id, $extension);
    }
}
