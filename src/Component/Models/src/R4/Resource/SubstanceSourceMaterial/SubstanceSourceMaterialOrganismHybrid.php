<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSourceMaterial;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description 4.9.13.8.1 Hybrid species maternal organism ID (Optional).
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSourceMaterial', elementPath: 'SubstanceSourceMaterial.organism.hybrid', fhirVersion: 'R4')]
class SubstanceSourceMaterialOrganismHybrid extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null maternalOrganismId The identifier of the maternal species constituting the hybrid organism shall be specified based on a controlled vocabulary. For plants, the parents aren’t always known, and it is unlikely that it will be known which is maternal and which is paternal */
        public StringPrimitive|string|null $maternalOrganismId = null,
        /** @var StringPrimitive|string|null maternalOrganismName The name of the maternal species constituting the hybrid organism shall be specified. For plants, the parents aren’t always known, and it is unlikely that it will be known which is maternal and which is paternal */
        public StringPrimitive|string|null $maternalOrganismName = null,
        /** @var StringPrimitive|string|null paternalOrganismId The identifier of the paternal species constituting the hybrid organism shall be specified based on a controlled vocabulary */
        public StringPrimitive|string|null $paternalOrganismId = null,
        /** @var StringPrimitive|string|null paternalOrganismName The name of the paternal species constituting the hybrid organism shall be specified */
        public StringPrimitive|string|null $paternalOrganismName = null,
        /** @var CodeableConcept|null hybridType The hybrid type of an organism shall be specified */
        public ?CodeableConcept $hybridType = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
