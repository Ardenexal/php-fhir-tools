<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element AuditEvent.entity
 * @description Specific instances of data or objects that have been accessed.
 */
class FHIRAuditEventEntity extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference what Specific instance of resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $what = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding type Type of entity involved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding role What role the entity played */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding $role = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding lifecycle Life-cycle stage for the entity */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding $lifecycle = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding> securityLabel Security labels on the entity */
		public array $securityLabel = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string name Descriptor for entity */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string description Descriptive text */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBase64Binary query Query parameters */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBase64Binary $query = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAuditEventEntityDetail> detail Additional Information about the entity */
		public array $detail = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
