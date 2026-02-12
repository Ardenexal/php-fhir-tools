<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Practitioner;

/**
 * @description The official certifications, training, and licenses that authorize or otherwise pertain to the provision of care by the practitioner.  For example, a medical license issued by a medical board authorizing the practitioner to practice medicine within a certian locality.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Practitioner', elementPath: 'Practitioner.qualification', fhirVersion: 'R4')]
class PractitionerQualification extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier An identifier for this qualification for the practitioner */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Coded representation of the qualification */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Period during which the qualification is valid */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference issuer Organization that regulates and issues the qualification */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $issuer = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
