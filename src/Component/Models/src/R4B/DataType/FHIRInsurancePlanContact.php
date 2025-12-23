<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element InsurancePlan.contact
 * @description The contact for the health insurance product for a certain purpose.
 */
class FHIRInsurancePlanContact extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept purpose The type of contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRHumanName name A name associated with the contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRHumanName $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRContactPoint> telecom Contact details (telephone, email, etc.)  for a contact */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAddress address Visiting or postal addresses for the contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAddress $address = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
