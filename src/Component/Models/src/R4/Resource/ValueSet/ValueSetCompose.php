<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet;

/**
 * @description A set of criteria that define the contents of the value set by including or excluding codes selected from the specified code system(s) that the value set draws from. This is also known as the Content Logical Definition (CLD).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.compose', fhirVersion: 'R4')]
class ValueSetCompose extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive lockedDate Fixed date for references with no specified version (transitive) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive $lockedDate = null,
		/** @var null|bool inactive Whether inactive codes are in the value set */
		public ?bool $inactive = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet\ValueSetComposeInclude> include Include one or more codes from a code system or other value set(s) */
		public array $include = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ValueSet\ValueSetComposeInclude> exclude Explicitly exclude codes from a code system or other value sets */
		public array $exclude = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
