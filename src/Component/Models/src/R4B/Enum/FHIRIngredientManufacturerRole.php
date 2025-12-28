<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

/**
 * ValueSet: IngredientManufacturerRole
 * URL: http://hl7.org/fhir/ValueSet/ingredient-manufacturer-role
 * Version: 4.3.0
 * Description: The way in which this manufacturer is associated with the ingredient. For example whether it is a possible one (others allowed), or an exclusive authorized one for this ingredient. Note that this is not the manufacturing process role.
 */
enum FHIRIngredientManufacturerRole: string
{
    /** Manufacturer is specifically allowed for this ingredient */
    case manufacturerisspecificallyallowedforthisingredient = 'allowed';

    /** Manufacturer is known to make this ingredient in general */
    case manufacturerisknowntomakethisingredientingeneral = 'possible';

    /** Manufacturer actually makes this particular ingredient */
    case manufactureractuallymakesthisparticularingredient = 'actual';
}
