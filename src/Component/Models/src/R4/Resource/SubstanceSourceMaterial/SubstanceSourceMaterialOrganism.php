<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSourceMaterial;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description This subclause describes the organism which the substance is derived from. For vaccines, the parent organism shall be specified based on these subclause elements. As an example, full taxonomy will be described for the Substance Name: ., Leaf.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSourceMaterial', elementPath: 'SubstanceSourceMaterial.organism', fhirVersion: 'R4')]
class SubstanceSourceMaterialOrganism extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null family The family of an organism shall be specified */
        public ?CodeableConcept $family = null,
        /** @var CodeableConcept|null genus The genus of an organism shall be specified; refers to the Latin epithet of the genus element of the plant/animal scientific name; it is present in names for genera, species and infraspecies */
        public ?CodeableConcept $genus = null,
        /** @var CodeableConcept|null species The species of an organism shall be specified; refers to the Latin epithet of the species of the plant/animal; it is present in names for species and infraspecies */
        public ?CodeableConcept $species = null,
        /** @var CodeableConcept|null intraspecificType The Intraspecific type of an organism shall be specified */
        public ?CodeableConcept $intraspecificType = null,
        /** @var StringPrimitive|string|null intraspecificDescription The intraspecific description of an organism shall be specified based on a controlled vocabulary. For Influenza Vaccine, the intraspecific description shall contain the syntax of the antigen in line with the WHO convention */
        public StringPrimitive|string|null $intraspecificDescription = null,
        /** @var array<SubstanceSourceMaterialOrganismAuthor> author 4.9.13.6.1 Author type (Conditional) */
        public array $author = [],
        /** @var SubstanceSourceMaterialOrganismHybrid|null hybrid 4.9.13.8.1 Hybrid species maternal organism ID (Optional) */
        public ?SubstanceSourceMaterialOrganismHybrid $hybrid = null,
        /** @var SubstanceSourceMaterialOrganismOrganismGeneral|null organismGeneral 4.9.13.7.1 Kingdom (Conditional) */
        public ?SubstanceSourceMaterialOrganismOrganismGeneral $organismGeneral = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
