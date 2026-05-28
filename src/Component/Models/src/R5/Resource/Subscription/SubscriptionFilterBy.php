<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Subscription;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\SearchComparatorType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\SearchModifierCodeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The filter properties to be applied to narrow the subscription topic stream.  When multiple filters are applied, evaluates to true if all the conditions applicable to that resource are met; otherwise it returns false (i.e., logical AND).
 */
#[FHIRBackboneElement(parentResource: 'Subscription', elementPath: 'Subscription.filterBy', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'scr-1',
    severity: 'error',
    expression: '(comparator.exists() and modifier.exists()).not()',
    human: 'Subscription filters may only contain a modifier or a comparator',
)]
class SubscriptionFilterBy extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var UriPrimitive|null resourceType Allowed Resource (reference to definition) for this Subscription filter */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/subscription-types', strength: 'extensible')]
        public ?UriPrimitive $resourceType = null,
        /** @var StringPrimitive|string|null filterParameter Filter label defined in SubscriptionTopic */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $filterParameter = null,
        /** @var SearchComparatorType|null comparator eq | ne | gt | lt | ge | le | sa | eb | ap */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/search-comparator|5.0.0', strength: 'required')]
        public ?SearchComparatorType $comparator = null,
        /** @var SearchModifierCodeType|null modifier missing | exact | contains | not | text | in | not-in | below | above | type | identifier | of-type | code-text | text-advanced | iterate */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/search-modifier-code|5.0.0', strength: 'required')]
        public ?SearchModifierCodeType $modifier = null,
        /** @var StringPrimitive|string|null value Literal value or resource path */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
