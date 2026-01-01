<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description 4.9.13.8.1 Hybrid species maternal organism ID (Optional).
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSourceMaterial', elementPath: 'SubstanceSourceMaterial.organism.hybrid', fhirVersion: 'R4')]
class FHIRSubstanceSourceMaterialOrganismHybrid extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null maternalOrganismId The identifier of the maternal species constituting the hybrid organism shall be specified based on a controlled vocabulary. For plants, the parents aren’t always known, and it is unlikely that it will be known which is maternal and which is paternal */
        public FHIRString|string|null $maternalOrganismId = null,
        /** @var FHIRString|string|null maternalOrganismName The name of the maternal species constituting the hybrid organism shall be specified. For plants, the parents aren’t always known, and it is unlikely that it will be known which is maternal and which is paternal */
        public FHIRString|string|null $maternalOrganismName = null,
        /** @var FHIRString|string|null paternalOrganismId The identifier of the paternal species constituting the hybrid organism shall be specified based on a controlled vocabulary */
        public FHIRString|string|null $paternalOrganismId = null,
        /** @var FHIRString|string|null paternalOrganismName The name of the paternal species constituting the hybrid organism shall be specified */
        public FHIRString|string|null $paternalOrganismName = null,
        /** @var FHIRCodeableConcept|null hybridType The hybrid type of an organism shall be specified */
        public ?FHIRCodeableConcept $hybridType = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
