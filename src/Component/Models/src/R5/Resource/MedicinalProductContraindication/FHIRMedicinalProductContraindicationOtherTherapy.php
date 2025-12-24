<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the use of the medicinal product in relation to other therapies described as part of the indication.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'MedicinalProductContraindication',
    elementPath: 'MedicinalProductContraindication.otherTherapy',
    fhirVersion: 'R5',
)]
class FHIRMedicinalProductContraindicationOtherTherapy extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null therapyRelationshipType The type of relationship between the medicinal product indication or contraindication and another therapy */
        #[NotBlank]
        public ?FHIRCodeableConcept $therapyRelationshipType = null,
        /** @var FHIRCodeableConcept|FHIRReference|null medicationX Reference to a specific medication (active substance, medicinal product or class of products) as part of an indication or contraindication */
        #[NotBlank]
        public FHIRCodeableConcept|FHIRReference|null $medicationX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
