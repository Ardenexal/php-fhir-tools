<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBase64Binary;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Specifies descriptive properties of the medicine, such as color, shape, imprints, etc.
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.drugCharacteristic', fhirVersion: 'R4B')]
class FHIRMedicationKnowledgeDrugCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Code specifying the type of characteristic of medication */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|FHIRString|string|FHIRQuantity|FHIRBase64Binary|null valueX Description of the characteristic */
        public FHIRCodeableConcept|FHIRString|string|FHIRQuantity|FHIRBase64Binary|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
