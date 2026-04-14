<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Binds to a value set if this element is coded (code, Coding, CodeableConcept, Quantity), or the data types (string, uri).
 */
#[FHIRComplexType(typeName: 'ElementDefinition.binding', fhirVersion: 'R5')]
class ElementDefinitionBinding extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var BindingStrengthType|null strength required | extensible | preferred | example */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?BindingStrengthType $strength = null,
        /** @var MarkdownPrimitive|null description Intended use of codes in the bound value set */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var CanonicalPrimitive|null valueSet Source of value set */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $valueSet = null,
        /** @var array<ElementDefinitionBindingAdditional> additional Additional Bindings - more rules about the binding */
        #[FhirProperty(
            fhirType: 'Element',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ElementDefinitionBindingAdditional',
        )]
        public array $additional = [],
    ) {
        parent::__construct($id, $extension);
    }
}
