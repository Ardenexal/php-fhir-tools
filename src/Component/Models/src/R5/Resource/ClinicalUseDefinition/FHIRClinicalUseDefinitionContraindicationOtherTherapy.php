<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the use of the medicinal product in relation to other therapies described as part of the contraindication.
 */
#[FHIRBackboneElement(
    parentResource: 'ClinicalUseDefinition',
    elementPath: 'ClinicalUseDefinition.contraindication.otherTherapy',
    fhirVersion: 'R5',
)]
class FHIRClinicalUseDefinitionContraindicationOtherTherapy extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null relationshipType The type of relationship between the product indication/contraindication and another therapy */
        #[NotBlank]
        public ?\FHIRCodeableConcept $relationshipType = null,
        /** @var FHIRCodeableReference|null treatment Reference to a specific medication, substance etc. as part of an indication or contraindication */
        #[NotBlank]
        public ?\FHIRCodeableReference $treatment = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
