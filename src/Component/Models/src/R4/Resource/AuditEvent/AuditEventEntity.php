<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent;

/**
 * @description Specific instances of data or objects that have been accessed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.entity', fhirVersion: 'R4')]
class AuditEventEntity extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference what Specific instance of resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $what = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding type Type of entity involved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding role What role the entity played */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $role = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding lifecycle Life-cycle stage for the entity */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $lifecycle = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding> securityLabel Security labels on the entity */
		public array $securityLabel = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Descriptor for entity */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Descriptive text */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive query Query parameters */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive $query = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent\AuditEventEntityDetail> detail Additional Information about the entity */
		public array $detail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
