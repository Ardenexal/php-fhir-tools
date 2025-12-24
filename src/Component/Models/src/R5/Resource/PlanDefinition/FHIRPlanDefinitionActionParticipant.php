<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Indicates who should participate in performing the action described.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'PlanDefinition', elementPath: 'PlanDefinition.action.participant', fhirVersion: 'R5')]
class FHIRPlanDefinitionActionParticipant extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null actorId What actor */
        public FHIRString|string|null $actorId = null,
        /** @var FHIRActionParticipantTypeType|null type careteam | device | group | healthcareservice | location | organization | patient | practitioner | practitionerrole | relatedperson */
        public ?FHIRActionParticipantTypeType $type = null,
        /** @var FHIRCanonical|null typeCanonical Who or what can participate */
        public ?FHIRCanonical $typeCanonical = null,
        /** @var FHIRReference|null typeReference Who or what can participate */
        public ?FHIRReference $typeReference = null,
        /** @var FHIRCodeableConcept|null role E.g. Nurse, Surgeon, Parent */
        public ?FHIRCodeableConcept $role = null,
        /** @var FHIRCodeableConcept|null function E.g. Author, Reviewer, Witness, etc */
        public ?FHIRCodeableConcept $function = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
