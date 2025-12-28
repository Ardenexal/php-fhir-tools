<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Characteristics for quantitative results of this observation.
 */
#[FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.quantitativeDetails', fhirVersion: 'R4B')]
class FHIRObservationDefinitionQuantitativeDetails extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null customaryUnit Customary unit for quantitative results */
        public ?\FHIRCodeableConcept $customaryUnit = null,
        /** @var FHIRCodeableConcept|null unit SI unit for quantitative results */
        public ?\FHIRCodeableConcept $unit = null,
        /** @var FHIRDecimal|null conversionFactor SI to Customary unit conversion factor */
        public ?\FHIRDecimal $conversionFactor = null,
        /** @var FHIRInteger|null decimalPrecision Decimal precision of observation quantitative results */
        public ?\FHIRInteger $decimalPrecision = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
