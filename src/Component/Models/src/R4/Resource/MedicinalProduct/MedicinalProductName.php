<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The product's name, including full name and possibly coded parts.
 */
#[FHIRBackboneElement(parentResource: 'MedicinalProduct', elementPath: 'MedicinalProduct.name', fhirVersion: 'R4')]
class MedicinalProductName extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null productName The full product name */
        #[NotBlank]
        public StringPrimitive|string|null $productName = null,
        /** @var array<MedicinalProductNameNamePart> namePart Coding words or phrases of the name */
        public array $namePart = [],
        /** @var array<MedicinalProductNameCountryLanguage> countryLanguage Country where the name applies */
        public array $countryLanguage = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
