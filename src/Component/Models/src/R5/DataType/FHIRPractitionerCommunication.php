<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Practitioner.communication
 * @description A language which may be used to communicate with the practitioner, often for correspondence/administrative purposes.

The `PractitionerRole.communication` property should be used for publishing the languages that a practitioner is able to communicate with patients (on a per Organization/Role basis).
 */
class FHIRPractitionerCommunication extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept language The language code used to communicate with the practitioner */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean preferred Language preference indicator */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $preferred = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
