<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Organization;

/**
 * @description Contact for the organization for a certain purpose.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Organization', elementPath: 'Organization.contact', fhirVersion: 'R4')]
class OrganizationContact extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept purpose The type of contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName name A name associated with the contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactPoint> telecom Contact details (telephone, email, etc.)  for a contact */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address address Visiting or postal addresses for the contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Address $address = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
