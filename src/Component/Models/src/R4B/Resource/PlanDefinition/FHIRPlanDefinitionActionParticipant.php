<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates who should participate in performing the action described.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action.participant', fhirVersion: 'R4B')]
class FHIRPlanDefinitionActionParticipant extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRActionParticipantTypeType|null type patient | practitioner | related-person | device */
        #[NotBlank]
        public ?FHIRActionParticipantTypeType $type = null,
        /** @var FHIRCodeableConcept|null role E.g. Nurse, Surgeon, Parent */
        public ?FHIRCodeableConcept $role = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
