<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Conveys instance-level information about this product item. One or several physical, countable instances or occurrences of the product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'NutritionProduct', elementPath: 'NutritionProduct.instance', fhirVersion: 'R5')]
class FHIRNutritionProductInstance extends FHIRBackboneElement
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
        /** @var array<FHIRIdentifier> identifier The identifier for the physical instance, typically a serial number or manufacturer number */
        public array $identifier = [],
        /** @var FHIRString|string|null name The name for the specific product */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null lotNumber The identification of the batch or lot of the product */
        public FHIRString|string|null $lotNumber = null,
        /** @var FHIRDateTime|null expiry The expiry date or date and time for the product */
        public ?FHIRDateTime $expiry = null,
        /** @var FHIRDateTime|null useBy The date until which the product is expected to be good for consumption */
        public ?FHIRDateTime $useBy = null,
        /** @var FHIRIdentifier|null biologicalSourceEvent An identifier that supports traceability to the event during which material in this product from one or more biological entities was obtained or pooled */
        public ?FHIRIdentifier $biologicalSourceEvent = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
