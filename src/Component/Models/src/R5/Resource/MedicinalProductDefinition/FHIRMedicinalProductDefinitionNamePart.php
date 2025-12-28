<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Coding words or phrases of the name.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProductDefinition', elementPath: 'MedicinalProductDefinition.name.part', fhirVersion: 'R5')]
class FHIRMedicinalProductDefinitionNamePart extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null part A fragment of a product name */
        #[NotBlank]
        public FHIRString|string|null $part = null,
        /** @var FHIRCodeableConcept|null type Identifying type for this part of the name (e.g. strength part) */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
