<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/ContactPoint
 * @description Details for all kinds of technology mediated contact points for a person or organization, including telephone, email, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'ContactPoint', fhirVersion: 'R4')]
class ContactPoint extends Element
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPointSystemType system phone | fax | email | pager | url | sms | other */
		public ?ContactPointSystemType $system = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string value The actual contact point details */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPointUseType use home | work | temp | old | mobile - purpose of this contact point */
		public ?ContactPointUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive rank Specify preferred order of use (1 = highest) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive $rank = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Time period when the contact point was/is in use */
		public ?Period $period = null,
	) {
		parent::__construct($id, $extension);
	}
}
