<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Contact for the organization for a certain purpose.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Organization', elementPath: 'Organization.contact', fhirVersion: 'R4')]
class FHIROrganizationContact extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept purpose The type of contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRHumanName name A name associated with the contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRHumanName $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRContactPoint> telecom Contact details (telephone, email, etc.)  for a contact */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAddress address Visiting or postal addresses for the contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAddress $address = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
