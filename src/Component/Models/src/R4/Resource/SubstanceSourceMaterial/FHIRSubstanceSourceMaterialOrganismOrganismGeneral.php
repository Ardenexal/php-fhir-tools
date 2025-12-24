<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;

/**
 * @description 4.9.13.7.1 Kingdom (Conditional).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'SubstanceSourceMaterial',
    elementPath: 'SubstanceSourceMaterial.organism.organismGeneral',
    fhirVersion: 'R4',
)]
class FHIRSubstanceSourceMaterialOrganismOrganismGeneral extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null kingdom The kingdom of an organism shall be specified */
        public ?FHIRCodeableConcept $kingdom = null,
        /** @var FHIRCodeableConcept|null phylum The phylum of an organism shall be specified */
        public ?FHIRCodeableConcept $phylum = null,
        /** @var FHIRCodeableConcept|null class The class of an organism shall be specified */
        public ?FHIRCodeableConcept $class = null,
        /** @var FHIRCodeableConcept|null order The order of an organism shall be specified, */
        public ?FHIRCodeableConcept $order = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
