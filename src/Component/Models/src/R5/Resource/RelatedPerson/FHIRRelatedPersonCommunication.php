<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A language which may be used to communicate with the related person about the patient's health.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'RelatedPerson', elementPath: 'RelatedPerson.communication', fhirVersion: 'R5')]
class FHIRRelatedPersonCommunication extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept language The language which can be used to communicate with the related person about the patient's health */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean preferred Language preference indicator */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $preferred = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
