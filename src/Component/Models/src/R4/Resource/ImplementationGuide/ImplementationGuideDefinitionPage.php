<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide;

/**
 * @description A page / section in the implementation guide. The root page is the implementation guide home page.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.definition.page', fhirVersion: 'R4')]
class ImplementationGuideDefinitionPage extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference nameX Where to find that page */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null $nameX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string title Short title shown for navigational assistance */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\GuidePageGenerationType generation html | markdown | xml | generated */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\GuidePageGenerationType $generation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideDefinitionPage> page Nested Pages / Sections */
		public array $page = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
