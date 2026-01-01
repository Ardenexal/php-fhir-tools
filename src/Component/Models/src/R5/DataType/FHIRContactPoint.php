<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/ContactPoint
 * @description Details for all kinds of technology mediated contact points for a person or organization, including telephone, email, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'ContactPoint', fhirVersion: 'R5')]
class FHIRContactPoint extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactPointSystemType system phone | fax | email | pager | url | sms | other */
		public ?FHIRContactPointSystemType $system = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string value The actual contact point details */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactPointUseType use home | work | temp | old | mobile - purpose of this contact point */
		public ?FHIRContactPointUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt rank Specify preferred order of use (1 = highest) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $rank = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod period Time period when the contact point was/is in use */
		public ?FHIRPeriod $period = null,
	) {
		parent::__construct($id, $extension);
	}
}
