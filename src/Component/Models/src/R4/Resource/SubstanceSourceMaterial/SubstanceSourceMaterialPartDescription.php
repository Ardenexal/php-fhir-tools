<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSourceMaterial;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description To do.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSourceMaterial', elementPath: 'SubstanceSourceMaterial.partDescription', fhirVersion: 'R4')]
class SubstanceSourceMaterialPartDescription extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null part Entity of anatomical origin of source material within an organism */
        public ?CodeableConcept $part = null,
        /** @var CodeableConcept|null partLocation The detailed anatomic location when the part can be extracted from different anatomical locations of the organism. Multiple alternative locations may apply */
        public ?CodeableConcept $partLocation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
