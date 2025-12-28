<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An actor taking an active role in the event or activity that is logged.
 */
#[FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.agent', fhirVersion: 'R4')]
class FHIRAuditEventAgent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type How agent participated */
        public ?\FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> role Agent role in the event */
        public array $role = [],
        /** @var FHIRReference|null who Identifier of who */
        public ?\FHIRReference $who = null,
        /** @var FHIRString|string|null altId Alternative User identity */
        public \FHIRString|string|null $altId = null,
        /** @var FHIRString|string|null name Human friendly name for the agent */
        public \FHIRString|string|null $name = null,
        /** @var FHIRBoolean|null requestor Whether user is initiator */
        #[NotBlank]
        public ?\FHIRBoolean $requestor = null,
        /** @var FHIRReference|null location Where */
        public ?\FHIRReference $location = null,
        /** @var array<FHIRUri> policy Policy that authorized event */
        public array $policy = [],
        /** @var FHIRCoding|null media Type of media */
        public ?\FHIRCoding $media = null,
        /** @var FHIRAuditEventAgentNetwork|null network Logical network location for application activity */
        public ?\FHIRAuditEventAgentNetwork $network = null,
        /** @var array<FHIRCodeableConcept> purposeOfUse Reason given for this user */
        public array $purposeOfUse = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
