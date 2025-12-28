<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Specific instances of data or objects that have been accessed.
 */
#[FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.entity', fhirVersion: 'R4B')]
class FHIRAuditEventEntity extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
        /** @var FHIRCoding|null type Type of entity involved */
        public ?\FHIRCoding $type = null,
        /** @var FHIRCoding|null role What role the entity played */
        public ?\FHIRCoding $role = null,
        /** @var FHIRCoding|null lifecycle Life-cycle stage for the entity */
        public ?\FHIRCoding $lifecycle = null,
        /** @var array<FHIRCoding> securityLabel Security labels on the entity */
        public array $securityLabel = [],
        /** @var FHIRString|string|null name Descriptor for entity */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null description Descriptive text */
        public \FHIRString|string|null $description = null,
        /** @var FHIRBase64Binary|null query Query parameters */
        public ?\FHIRBase64Binary $query = null,
        /** @var array<FHIRAuditEventEntityDetail> detail Additional Information about the entity */
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
