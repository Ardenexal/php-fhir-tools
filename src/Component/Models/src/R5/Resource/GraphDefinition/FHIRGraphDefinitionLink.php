<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Links this graph makes rules about.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'GraphDefinition', elementPath: 'GraphDefinition.link', fhirVersion: 'R5')]
class FHIRGraphDefinitionLink extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string description Why this link is specified */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger min Minimum occurrences for this link */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $min = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string max Maximum occurrences for this link */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $max = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId sourceId Source Node for this link */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $sourceId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string path Path in the resource that contains the link */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $path = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string sliceName Which slice (if profiled) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $sliceName = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId targetId Target Node for this link */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $targetId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string params Criteria for reverse lookup */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $params = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGraphDefinitionLinkCompartment> compartment Compartment Consistency Rules */
		public array $compartment = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
