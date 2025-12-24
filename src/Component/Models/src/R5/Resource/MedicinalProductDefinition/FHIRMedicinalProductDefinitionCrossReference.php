<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Reference to another product, e.g. for linking authorised to investigational product, or a virtual product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'MedicinalProductDefinition',
    elementPath: 'MedicinalProductDefinition.crossReference',
    fhirVersion: 'R5',
)]
class FHIRMedicinalProductDefinitionCrossReference extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null product Reference to another product, e.g. for linking authorised to investigational product */
        #[NotBlank]
        public ?FHIRCodeableReference $product = null,
        /** @var FHIRCodeableConcept|null type The type of relationship, for instance branded to generic or virtual to actual product */
        public ?FHIRCodeableConcept $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
