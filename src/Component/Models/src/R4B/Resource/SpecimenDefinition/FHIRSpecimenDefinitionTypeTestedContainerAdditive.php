<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Substance introduced in the kind of container to preserve, maintain or enhance the specimen. Examples: Formalin, Citrate, EDTA.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'SpecimenDefinition',
	elementPath: 'SpecimenDefinition.typeTested.container.additive',
	fhirVersion: 'R4B',
)]
class FHIRSpecimenDefinitionTypeTestedContainerAdditive extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference additiveX Additive associated with container */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference|null $additiveX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
