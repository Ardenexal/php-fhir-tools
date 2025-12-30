<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Conveys instance-level information about this product item. One or several physical, countable instances or occurrences of the product.
 */
#[FHIRBackboneElement(parentResource: 'NutritionProduct', elementPath: 'NutritionProduct.instance', fhirVersion: 'R4B')]
class FHIRNutritionProductInstance extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRQuantity|null quantity The amount of items or instances */
        public ?FHIRQuantity $quantity = null,
        /** @var array<FHIRIdentifier> identifier The identifier for the physical instance, typically a serial number */
        public array $identifier = [],
        /** @var FHIRString|string|null lotNumber The identification of the batch or lot of the product */
        public FHIRString|string|null $lotNumber = null,
        /** @var FHIRDateTime|null expiry The expiry date or date and time for the product */
        public ?FHIRDateTime $expiry = null,
        /** @var FHIRDateTime|null useBy The date until which the product is expected to be good for consumption */
        public ?FHIRDateTime $useBy = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
