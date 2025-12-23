<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element AuditEvent.entity
 * @description Specific instances of data or objects that have been accessed.
 */
class FHIRAuditEventEntity extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference what Specific instance of resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $what = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept role What role the entity played */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $role = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> securityLabel Security labels on the entity */
		public array $securityLabel = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBase64Binary query Query parameters */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBase64Binary $query = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAuditEventEntityDetail> detail Additional Information about the entity */
		public array $detail = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAuditEventAgent> agent Entity is attributed to this agent */
		public array $agent = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
