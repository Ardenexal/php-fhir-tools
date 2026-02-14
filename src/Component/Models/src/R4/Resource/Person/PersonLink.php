<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Person;

/**
 * @description Link to a resource that concerns the same actual person.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Person', elementPath: 'Person.link', fhirVersion: 'R4')]
class PersonLink extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference target The resource to which this actual person is associated */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $target = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\IdentityAssuranceLevelType assurance level1 | level2 | level3 | level4 */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\IdentityAssuranceLevelType $assurance = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
