<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSourceMaterial;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description 4.9.13.7.1 Kingdom (Conditional).
 */
#[FHIRBackboneElement(
    parentResource: 'SubstanceSourceMaterial',
    elementPath: 'SubstanceSourceMaterial.organism.organismGeneral',
    fhirVersion: 'R4',
)]
class SubstanceSourceMaterialOrganismOrganismGeneral extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null kingdom The kingdom of an organism shall be specified */
        public ?CodeableConcept $kingdom = null,
        /** @var CodeableConcept|null phylum The phylum of an organism shall be specified */
        public ?CodeableConcept $phylum = null,
        /** @var CodeableConcept|null class The class of an organism shall be specified */
        public ?CodeableConcept $class = null,
        /** @var CodeableConcept|null order The order of an organism shall be specified, */
        public ?CodeableConcept $order = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
