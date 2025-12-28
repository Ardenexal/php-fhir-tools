<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Specific instances of data or objects that have been accessed.
 */
#[FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.entity', fhirVersion: 'R5')]
class FHIRAuditEventEntity extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null what Specific instance of resource */
        public ?\FHIRReference $what = null,
        /** @var FHIRCodeableConcept|null role What role the entity played */
        public ?\FHIRCodeableConcept $role = null,
        /** @var array<FHIRCodeableConcept> securityLabel Security labels on the entity */
        public array $securityLabel = [],
        /** @var FHIRBase64Binary|null query Query parameters */
        public ?\FHIRBase64Binary $query = null,
        /** @var array<FHIRAuditEventEntityDetail> detail Additional Information about the entity */
        public array $detail = [],
        /** @var array<FHIRAuditEventAgent> agent Entity is attributed to this agent */
        public array $agent = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
