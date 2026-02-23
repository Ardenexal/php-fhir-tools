<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Formal constraints such as co-occurrence and other constraints that can be computationally evaluated within the context of the instance.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.constraint', fhirVersion: 'R4B')]
class ElementDefinitionConstraint extends Element
{
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.String',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'key' => [
            'fhirType'     => 'id',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'requirements' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'severity' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'human' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'expression' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'xpath' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'source' => [
            'fhirType'     => 'canonical',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var IdPrimitive|null key Target of 'condition' reference above */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?IdPrimitive $key = null,
        /** @var StringPrimitive|string|null requirements Why this constraint is necessary or appropriate */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $requirements = null,
        /** @var ConstraintSeverityType|null severity error | warning */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?ConstraintSeverityType $severity = null,
        /** @var StringPrimitive|string|null human Human description of constraint */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $human = null,
        /** @var StringPrimitive|string|null expression FHIRPath expression of constraint */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $expression = null,
        /** @var StringPrimitive|string|null xpath XPath expression of constraint */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $xpath = null,
        /** @var CanonicalPrimitive|null source Reference to original source of constraint */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $source = null,
    ) {
        parent::__construct($id, $extension);
    }
}
