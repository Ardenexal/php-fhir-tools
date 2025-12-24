<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRElement;

/**
 * @description The amount of medication administered.
 */
#[FHIRComplexType(typeName: 'Dosage.doseAndRate', fhirVersion: 'R4')]
class FHIRDosageDoseAndRate extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRCodeableConcept|null type The kind of dose or rate specified */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRRange|FHIRQuantity|null doseX Amount of medication per dose */
        public FHIRRange|FHIRQuantity|null $doseX = null,
        /** @var FHIRRatio|FHIRRange|FHIRQuantity|null rateX Amount of medication per unit of time */
        public FHIRRatio|FHIRRange|FHIRQuantity|null $rateX = null,
    ) {
        parent::__construct($id, $extension);
    }
}
