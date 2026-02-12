<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct;

/**
 * @description The product's name, including full name and possibly coded parts.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicinalProduct', elementPath: 'MedicinalProduct.name', fhirVersion: 'R4')]
class MedicinalProductName extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string productName The full product name */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $productName = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct\MedicinalProductNameNamePart> namePart Coding words or phrases of the name */
		public array $namePart = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct\MedicinalProductNameCountryLanguage> countryLanguage Country where the name applies */
		public array $countryLanguage = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
