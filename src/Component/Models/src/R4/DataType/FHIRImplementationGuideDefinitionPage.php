<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ImplementationGuide.definition.page
 * @description A page / section in the implementation guide. The root page is the implementation guide home page.
 */
class FHIRImplementationGuideDefinitionPage extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference nameX Where to find that page */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference|null $nameX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string title Short title shown for navigational assistance */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRGuidePageGenerationType generation html | markdown | xml | generated */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRGuidePageGenerationType $generation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRImplementationGuideDefinitionPage> page Nested Pages / Sections */
		public array $page = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
