<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An actor taking an active role in the event or activity that is logged.
 */
#[FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.agent', fhirVersion: 'R5')]
class FHIRAuditEventAgent extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type How agent participated */
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> role Agent role in the event */
        public array $role = [],
        /** @var FHIRReference|null who Identifier of who */
        #[NotBlank]
        public ?FHIRReference $who = null,
        /** @var FHIRBoolean|null requestor Whether user is initiator */
        public ?FHIRBoolean $requestor = null,
        /** @var FHIRReference|null location The agent location when the event occurred */
        public ?FHIRReference $location = null,
        /** @var array<FHIRUri> policy Policy that authorized the agent participation in the event */
        public array $policy = [],
        /** @var FHIRReference|FHIRUri|FHIRString|string|null networkX This agent network location for the activity */
        public FHIRReference|FHIRUri|FHIRString|string|null $networkX = null,
        /** @var array<FHIRCodeableConcept> authorization Allowable authorization for this agent */
        public array $authorization = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
