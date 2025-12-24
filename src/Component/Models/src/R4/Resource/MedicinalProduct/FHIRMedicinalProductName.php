<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The product's name, including full name and possibly coded parts.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicinalProduct', elementPath: 'MedicinalProduct.name', fhirVersion: 'R4')]
class FHIRMedicinalProductName extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null productName The full product name */
        #[NotBlank]
        public FHIRString|string|null $productName = null,
        /** @var array<FHIRMedicinalProductNameNamePart> namePart Coding words or phrases of the name */
        public array $namePart = [],
        /** @var array<FHIRMedicinalProductNameCountryLanguage> countryLanguage Country where the name applies */
        public array $countryLanguage = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
