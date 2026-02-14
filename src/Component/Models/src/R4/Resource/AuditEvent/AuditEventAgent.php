<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An actor taking an active role in the event or activity that is logged.
 */
#[FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.agent', fhirVersion: 'R4')]
class AuditEventAgent extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type How agent participated */
        public ?CodeableConcept $type = null,
        /** @var array<CodeableConcept> role Agent role in the event */
        public array $role = [],
        /** @var Reference|null who Identifier of who */
        public ?Reference $who = null,
        /** @var StringPrimitive|string|null altId Alternative User identity */
        public StringPrimitive|string|null $altId = null,
        /** @var StringPrimitive|string|null name Human friendly name for the agent */
        public StringPrimitive|string|null $name = null,
        /** @var bool|null requestor Whether user is initiator */
        #[NotBlank]
        public ?bool $requestor = null,
        /** @var Reference|null location Where */
        public ?Reference $location = null,
        /** @var array<UriPrimitive> policy Policy that authorized event */
        public array $policy = [],
        /** @var Coding|null media Type of media */
        public ?Coding $media = null,
        /** @var AuditEventAgentNetwork|null network Logical network location for application activity */
        public ?AuditEventAgentNetwork $network = null,
        /** @var array<CodeableConcept> purposeOfUse Reason given for this user */
        public array $purposeOfUse = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
