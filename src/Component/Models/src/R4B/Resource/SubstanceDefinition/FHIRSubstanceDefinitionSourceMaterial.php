<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Material or taxonomic/anatomical source for the substance.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceDefinition', elementPath: 'SubstanceDefinition.sourceMaterial', fhirVersion: 'R4B')]
class FHIRSubstanceDefinitionSourceMaterial extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Classification of the origin of the raw material. e.g. cat hair is an Animal source type */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null genus The genus of an organism e.g. the Latin epithet of the plant/animal scientific name */
        public ?\FHIRCodeableConcept $genus = null,
        /** @var FHIRCodeableConcept|null species The species of an organism e.g. the Latin epithet of the species of the plant/animal */
        public ?\FHIRCodeableConcept $species = null,
        /** @var FHIRCodeableConcept|null part An anatomical origin of the source material within an organism */
        public ?\FHIRCodeableConcept $part = null,
        /** @var array<FHIRCodeableConcept> countryOfOrigin The country or countries where the material is harvested */
        public array $countryOfOrigin = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
