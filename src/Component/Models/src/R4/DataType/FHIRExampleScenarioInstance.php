<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ExampleScenario.instance
 * @description Each resource and each version that is present in the workflow.
 */
class FHIRExampleScenarioInstance extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string resourceId The id of the resource for referencing */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $resourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResourceTypeType resourceType The type of the resource */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResourceTypeType $resourceType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string name A short name for the resource instance */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown description Human-friendly description of the resource instance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExampleScenarioInstanceVersion> version A specific version of the resource */
		public array $version = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExampleScenarioInstanceContainedInstance> containedInstance Resources contained in the instance */
		public array $containedInstance = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
