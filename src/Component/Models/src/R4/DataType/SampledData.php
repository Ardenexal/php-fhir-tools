<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/SampledData
 *
 * @description A series of measurements taken by a device, with upper and lower limits. There may be more than one dimension in the data.
 */
#[FHIRComplexType(typeName: 'SampledData', fhirVersion: 'R4')]
class SampledData extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var Quantity|null origin Zero value and units */
        #[NotBlank]
        public ?Quantity $origin = null,
        /** @var float|null period Number of milliseconds between samples */
        #[NotBlank]
        public ?float $period = null,
        /** @var float|null factor Multiply data by this before adding to origin */
        public ?float $factor = null,
        /** @var float|null lowerLimit Lower limit of detection */
        public ?float $lowerLimit = null,
        /** @var float|null upperLimit Upper limit of detection */
        public ?float $upperLimit = null,
        /** @var PositiveIntPrimitive|null dimensions Number of sample points at each time point */
        #[NotBlank]
        public ?PositiveIntPrimitive $dimensions = null,
        /** @var StringPrimitive|string|null data Decimal values with spaces, or "E" | "U" | "L" */
        public StringPrimitive|string|null $data = null,
    ) {
        parent::__construct($id, $extension);
    }
}
