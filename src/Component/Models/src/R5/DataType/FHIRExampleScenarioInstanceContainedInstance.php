<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ExampleScenario.instance.containedInstance
 * @description References to other instances that can be found within this instance (e.g. the observations contained in a bundle).
 */
class FHIRExampleScenarioInstanceContainedInstance extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string instanceReference Key of contained instance */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $instanceReference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string versionReference Key of contained instance version */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $versionReference = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
