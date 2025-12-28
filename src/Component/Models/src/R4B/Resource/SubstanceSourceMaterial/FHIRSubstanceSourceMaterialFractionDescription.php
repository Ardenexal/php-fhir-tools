<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Many complex materials are fractions of parts of plants, animals, or minerals. Fraction elements are often necessary to define both Substances and Specified Group 1 Substances. For substances derived from Plants, fraction information will be captured at the Substance information level ( . Oils, Juices and Exudates). Additional information for Extracts, such as extraction solvent composition, will be captured at the Specified Substance Group 1 information level. For plasma-derived products fraction information will be captured at the Substance and the Specified Substance Group 1 levels.
 */
#[FHIRBackboneElement(
    parentResource: 'SubstanceSourceMaterial',
    elementPath: 'SubstanceSourceMaterial.fractionDescription',
    fhirVersion: 'R4B',
)]
class FHIRSubstanceSourceMaterialFractionDescription extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null fraction This element is capturing information about the fraction of a plant part, or human plasma for fractionation */
        public \FHIRString|string|null $fraction = null,
        /** @var FHIRCodeableConcept|null materialType The specific type of the material constituting the component. For Herbal preparations the particulars of the extracts (liquid/dry) is described in Specified Substance Group 1 */
        public ?\FHIRCodeableConcept $materialType = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
