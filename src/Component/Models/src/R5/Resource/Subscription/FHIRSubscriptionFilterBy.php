<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The filter properties to be applied to narrow the subscription topic stream.  When multiple filters are applied, evaluates to true if all the conditions applicable to that resource are met; otherwise it returns false (i.e., logical AND).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Subscription', elementPath: 'Subscription.filterBy', fhirVersion: 'R5')]
class FHIRSubscriptionFilterBy extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri resourceType Allowed Resource (reference to definition) for this Subscription filter */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $resourceType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string filterParameter Filter label defined in SubscriptionTopic */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $filterParameter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchComparatorType comparator eq | ne | gt | lt | ge | le | sa | eb | ap */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchComparatorType $comparator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchModifierCodeType modifier missing | exact | contains | not | text | in | not-in | below | above | type | identifier | of-type | code-text | text-advanced | iterate */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRSearchModifierCodeType $modifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string value Literal value or resource path */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $value = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
