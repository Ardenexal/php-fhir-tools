<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Specific instances of data or objects that have been accessed.
 */
#[FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.entity', fhirVersion: 'R4')]
class AuditEventEntity extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null what Specific instance of resource */
        public ?Reference $what = null,
        /** @var Coding|null type Type of entity involved */
        public ?Coding $type = null,
        /** @var Coding|null role What role the entity played */
        public ?Coding $role = null,
        /** @var Coding|null lifecycle Life-cycle stage for the entity */
        public ?Coding $lifecycle = null,
        /** @var array<Coding> securityLabel Security labels on the entity */
        public array $securityLabel = [],
        /** @var StringPrimitive|string|null name Descriptor for entity */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null description Descriptive text */
        public StringPrimitive|string|null $description = null,
        /** @var Base64BinaryPrimitive|null query Query parameters */
        public ?Base64BinaryPrimitive $query = null,
        /** @var array<AuditEventEntityDetail> detail Additional Information about the entity */
        public array $detail = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
