<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Age
 * @description A duration of time during which an organism (or a process) has existed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Age', fhirVersion: 'R4')]
class FHIRAge extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal value Numerical value (with implicit precision) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDecimal $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantityComparatorType comparator < | <= | >= | > - how to understand the value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantityComparatorType $comparator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string unit Unit representation */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $unit = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri system System that defines coded unit form */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri $system = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode code Coded form of the unit */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode $code = null,
	) {
		parent::__construct($id, $extension, $value, $comparator, $unit, $system, $code);
	}
}
