<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @description The amount of medication administered.
 */
#[FHIRComplexType(typeName: 'Dosage.doseAndRate', fhirVersion: 'R4')]
class DosageDoseAndRate extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var CodeableConcept|null type The kind of dose or rate specified */
        public ?CodeableConcept $type = null,
        /** @var Range|Quantity|null doseX Amount of medication per dose */
        public Range|Quantity|null $doseX = null,
        /** @var Ratio|Range|Quantity|null rateX Amount of medication per unit of time */
        public Ratio|Range|Quantity|null $rateX = null,
    ) {
        parent::__construct($id, $extension);
    }
}
