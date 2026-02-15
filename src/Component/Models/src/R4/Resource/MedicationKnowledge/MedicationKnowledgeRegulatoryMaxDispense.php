<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationKnowledge;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The maximum number of units of the medication that can be dispensed in a period.
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.regulatory.maxDispense', fhirVersion: 'R4')]
class MedicationKnowledgeRegulatoryMaxDispense extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Quantity|null quantity The maximum number of units of the medication that can be dispensed */
        #[NotBlank]
        public ?Quantity $quantity = null,
        /** @var Duration|null period The period that applies to the maximum number of units */
        public ?Duration $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
