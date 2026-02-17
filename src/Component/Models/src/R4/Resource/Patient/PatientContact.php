<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Patient;

/**
 * @description A contact party (e.g. guardian, partner, friend) for the patient.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Patient', elementPath: 'Patient.contact', fhirVersion: 'R4')]
class PatientContact extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> relationship The kind of relationship */
		public array $relationship = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName name A name associated with the contact person */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint> telecom A contact detail for the person */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address address Address for the contact person */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address $address = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType gender male | female | other | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference organization Organization that is associated with the contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $organization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period The period during which this contact person or organization is valid to be contacted relating to this patient */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
