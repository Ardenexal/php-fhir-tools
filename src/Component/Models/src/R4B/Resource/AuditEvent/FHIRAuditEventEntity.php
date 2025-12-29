<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Specific instances of data or objects that have been accessed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.entity', fhirVersion: 'R4B')]
class FHIRAuditEventEntity extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference what Specific instance of resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $what = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding type Type of entity involved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding role What role the entity played */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding $role = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding lifecycle Life-cycle stage for the entity */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding $lifecycle = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding> securityLabel Security labels on the entity */
		public array $securityLabel = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string name Descriptor for entity */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string description Descriptive text */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBase64Binary query Query parameters */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBase64Binary $query = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAuditEventEntityDetail> detail Additional Information about the entity */
		public array $detail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
