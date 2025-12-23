<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Patient.contact
 * @description A contact party (e.g. guardian, partner, friend) for the patient.
 */
class FHIRPatientContact extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> relationship The kind of relationship */
		public array $relationship = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRHumanName name A name associated with the contact person */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRHumanName $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRContactPoint> telecom A contact detail for the person */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAddress address Address for the contact person */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAddress $address = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAdministrativeGenderType gender male | female | other | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAdministrativeGenderType $gender = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference organization Organization that is associated with the contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference $organization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod period The period during which this contact person or organization is valid to be contacted relating to this patient */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod $period = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
