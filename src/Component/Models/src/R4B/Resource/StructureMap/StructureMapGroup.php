<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\StructureMap;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\StructureMapGroupTypeModeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Organizes the mapping into manageable chunks for human review/ease of maintenance.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group', fhirVersion: 'R4B')]
class StructureMapGroup extends BackboneElement
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
        'modifierExtension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'modifierExtension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'name' => [
            'fhirType'     => 'id',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'extends' => [
            'fhirType'     => 'id',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'typeMode' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'documentation' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'input' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => true,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'rule' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => true,
            'isRequired'   => true,
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
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var IdPrimitive|null name Human-readable label */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?IdPrimitive $name = null,
        /** @var IdPrimitive|null extends Another group that this group adds rules to */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $extends = null,
        /** @var StructureMapGroupTypeModeType|null typeMode none | types | type-and-types */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?StructureMapGroupTypeModeType $typeMode = null,
        /** @var StringPrimitive|string|null documentation Additional description/explanation for group */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $documentation = null,
        /** @var array<StructureMapGroupInput> input Named instance provided when invoking the map */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true, isRequired: true)]
        public array $input = [],
        /** @var array<StructureMapGroupRule> rule Transform Rule from source to target */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true, isRequired: true)]
        public array $rule = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
