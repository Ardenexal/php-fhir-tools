<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Allows the key product features to be recorded, such as "sugar free", "modified release", "parallel import".
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'MedicinalProductDefinition',
	elementPath: 'MedicinalProductDefinition.characteristic',
	fhirVersion: 'R5',
)]
class FHIRMedicinalProductDefinitionCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type A code expressing the type of characteristic */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment valueX A value for the characteristic */
		public \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment|null $valueX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
