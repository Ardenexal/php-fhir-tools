<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Person.link
 * @description Link to a resource that concerns the same actual person.
 */
class FHIRPersonLink extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference target The resource to which this actual person is associated */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $target = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentityAssuranceLevelType assurance level1 | level2 | level3 | level4 */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentityAssuranceLevelType $assurance = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
