<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;

/**
 * @description A manufacturing or administrative process or step associated with (or performed on) the medicinal product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicinalProductDefinition', elementPath: 'MedicinalProductDefinition.operation', fhirVersion: 'R4B')]
class FHIRMedicinalProductDefinitionOperation extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null type The type of manufacturing operation e.g. manufacturing itself, re-packaging */
        public ?FHIRCodeableReference $type = null,
        /** @var FHIRPeriod|null effectiveDate Date range of applicability */
        public ?FHIRPeriod $effectiveDate = null,
        /** @var array<FHIRReference> organization The organization responsible for the particular process, e.g. the manufacturer or importer */
        public array $organization = [],
        /** @var FHIRCodeableConcept|null confidentialityIndicator Specifies whether this process is considered proprietary or confidential */
        public ?FHIRCodeableConcept $confidentialityIndicator = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
