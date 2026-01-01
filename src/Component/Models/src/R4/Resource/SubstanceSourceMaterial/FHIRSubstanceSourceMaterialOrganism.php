<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description This subclause describes the organism which the substance is derived from. For vaccines, the parent organism shall be specified based on these subclause elements. As an example, full taxonomy will be described for the Substance Name: ., Leaf.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSourceMaterial', elementPath: 'SubstanceSourceMaterial.organism', fhirVersion: 'R4')]
class FHIRSubstanceSourceMaterialOrganism extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null family The family of an organism shall be specified */
        public ?FHIRCodeableConcept $family = null,
        /** @var FHIRCodeableConcept|null genus The genus of an organism shall be specified; refers to the Latin epithet of the genus element of the plant/animal scientific name; it is present in names for genera, species and infraspecies */
        public ?FHIRCodeableConcept $genus = null,
        /** @var FHIRCodeableConcept|null species The species of an organism shall be specified; refers to the Latin epithet of the species of the plant/animal; it is present in names for species and infraspecies */
        public ?FHIRCodeableConcept $species = null,
        /** @var FHIRCodeableConcept|null intraspecificType The Intraspecific type of an organism shall be specified */
        public ?FHIRCodeableConcept $intraspecificType = null,
        /** @var FHIRString|string|null intraspecificDescription The intraspecific description of an organism shall be specified based on a controlled vocabulary. For Influenza Vaccine, the intraspecific description shall contain the syntax of the antigen in line with the WHO convention */
        public FHIRString|string|null $intraspecificDescription = null,
        /** @var array<FHIRSubstanceSourceMaterialOrganismAuthor> author 4.9.13.6.1 Author type (Conditional) */
        public array $author = [],
        /** @var FHIRSubstanceSourceMaterialOrganismHybrid|null hybrid 4.9.13.8.1 Hybrid species maternal organism ID (Optional) */
        public ?FHIRSubstanceSourceMaterialOrganismHybrid $hybrid = null,
        /** @var FHIRSubstanceSourceMaterialOrganismOrganismGeneral|null organismGeneral 4.9.13.7.1 Kingdom (Conditional) */
        public ?FHIRSubstanceSourceMaterialOrganismOrganismGeneral $organismGeneral = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
