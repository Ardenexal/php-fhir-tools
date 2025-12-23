<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ExampleScenario.actor
 * @description Actor participating in the resource.
 */
class FHIRExampleScenarioActor extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string actorId ID or acronym of the actor */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $actorId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExampleScenarioActorTypeType type person | entity */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExampleScenarioActorTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string name The name of the actor as shown in the page */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown description The description of the actor */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown $description = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
