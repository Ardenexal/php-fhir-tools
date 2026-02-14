<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

/**
 * @description Capabilities that must exist and are assumed to function correctly on the FHIR server being tested.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.metadata.capability', fhirVersion: 'R4')]
class TestScriptMetadataCapability extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|bool required Are the capabilities required? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?bool $required = null,
		/** @var null|bool validated Are the capabilities validated? */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?bool $validated = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description The expected capabilities of the server */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var  array<int> origin Which origin server these requirements apply to */
		public array $origin = [],
		/** @var null|int destination Which server these requirements apply to */
		public ?int $destination = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive> link Links to the FHIR specification */
		public array $link = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive capabilities Required Capability Statement */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $capabilities = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
