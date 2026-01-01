<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A single data collection that is shared as part of the scenario.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.instance', fhirVersion: 'R5')]
class FHIRExampleScenarioInstance extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string key ID or acronym of the instance */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $key = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding structureType Data structure for example */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding $structureType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string structureVersion E.g. 4.0.1 */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $structureVersion = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri structureProfileX Rules instance adheres to */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri|null $structureProfileX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string title Label for instance */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description Human-friendly description of the instance */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference content Example instance data */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $content = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioInstanceVersion> version Snapshot of instance that changes */
		public array $version = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExampleScenarioInstanceContainedInstance> containedInstance Resources contained in the instance */
		public array $containedInstance = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
