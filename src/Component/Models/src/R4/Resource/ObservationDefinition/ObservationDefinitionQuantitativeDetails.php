<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description Characteristics for quantitative results of this observation.
 */
#[FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.quantitativeDetails', fhirVersion: 'R4')]
class ObservationDefinitionQuantitativeDetails extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null customaryUnit Customary unit for quantitative results */
        public ?CodeableConcept $customaryUnit = null,
        /** @var CodeableConcept|null unit SI unit for quantitative results */
        public ?CodeableConcept $unit = null,
        /** @var float|null conversionFactor SI to Customary unit conversion factor */
        public ?float $conversionFactor = null,
        /** @var int|null decimalPrecision Decimal precision of observation quantitative results */
        public ?int $decimalPrecision = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
