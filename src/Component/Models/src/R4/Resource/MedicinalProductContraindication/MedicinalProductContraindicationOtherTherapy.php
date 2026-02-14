<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductContraindication;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the use of the medicinal product in relation to other therapies described as part of the indication.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductContraindication',
    elementPath: 'MedicinalProductContraindication.otherTherapy',
    fhirVersion: 'R4',
)]
class MedicinalProductContraindicationOtherTherapy extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null therapyRelationshipType The type of relationship between the medicinal product indication or contraindication and another therapy */
        #[NotBlank]
        public ?CodeableConcept $therapyRelationshipType = null,
        /** @var CodeableConcept|Reference|null medicationX Reference to a specific medication (active substance, medicinal product or class of products) as part of an indication or contraindication */
        #[NotBlank]
        public CodeableConcept|Reference|null $medicationX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
