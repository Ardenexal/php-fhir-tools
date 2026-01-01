<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Plan that is appropriate.
 */
#[FHIRBackboneElement(parentResource: 'ConditionDefinition', elementPath: 'ConditionDefinition.plan', fhirVersion: 'R5')]
class FHIRConditionDefinitionPlan extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null role Use for the plan */
        public ?FHIRCodeableConcept $role = null,
        /** @var FHIRReference|null reference The actual plan */
        #[NotBlank]
        public ?FHIRReference $reference = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
