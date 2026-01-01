<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchComparatorType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchModifierCodeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The filter properties to be applied to narrow the subscription topic stream.  When multiple filters are applied, evaluates to true if all the conditions applicable to that resource are met; otherwise it returns false (i.e., logical AND).
 */
#[FHIRBackboneElement(parentResource: 'Subscription', elementPath: 'Subscription.filterBy', fhirVersion: 'R5')]
class FHIRSubscriptionFilterBy extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|null resourceType Allowed Resource (reference to definition) for this Subscription filter */
        public ?FHIRUri $resourceType = null,
        /** @var FHIRString|string|null filterParameter Filter label defined in SubscriptionTopic */
        #[NotBlank]
        public FHIRString|string|null $filterParameter = null,
        /** @var FHIRSearchComparatorType|null comparator eq | ne | gt | lt | ge | le | sa | eb | ap */
        public ?FHIRSearchComparatorType $comparator = null,
        /** @var FHIRSearchModifierCodeType|null modifier missing | exact | contains | not | text | in | not-in | below | above | type | identifier | of-type | code-text | text-advanced | iterate */
        public ?FHIRSearchModifierCodeType $modifier = null,
        /** @var FHIRString|string|null value Literal value or resource path */
        #[NotBlank]
        public FHIRString|string|null $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
