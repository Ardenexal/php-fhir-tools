<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Additional bindings that help applications implementing this element. Additional bindings do not replace the main binding but provide more information and/or context.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.binding.additional', fhirVersion: 'R5')]
class ElementDefinitionBindingAdditional extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var AdditionalBindingPurposeVSType|null purpose maximum | minimum | required | extensible | candidate | current | preferred | ui | starter | component */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?AdditionalBindingPurposeVSType $purpose = null,
        /** @var CanonicalPrimitive|null valueSet The value set for the additional binding */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?CanonicalPrimitive $valueSet = null,
        /** @var MarkdownPrimitive|null documentation Documentation of the purpose of use of the binding */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $documentation = null,
        /** @var StringPrimitive|string|null shortDoco Concise documentation - for summary tables */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $shortDoco = null,
        /** @var array<UsageContext> usage Qualifies the usage - jurisdiction, gender, workflow status etc. */
        #[FhirProperty(
            fhirType: 'UsageContext',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext',
        )]
        public array $usage = [],
        /** @var bool|null any Whether binding can applies to all repeats, or just one */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $any = null,
    ) {
        parent::__construct($id, $extension);
    }
}
