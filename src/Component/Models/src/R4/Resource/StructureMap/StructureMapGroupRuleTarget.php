<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureMapContextTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureMapTargetListModeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\StructureMapTransformType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Content to create because of this mapping rule.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.target', fhirVersion: 'R4')]
class StructureMapGroupRuleTarget extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var IdPrimitive|null context Type or variable this rule applies to */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $context = null,
        /** @var StructureMapContextTypeType|null contextType type | variable */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?StructureMapContextTypeType $contextType = null,
        /** @var StringPrimitive|string|null element Field to create in the context */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $element = null,
        /** @var IdPrimitive|null variable Named context for field, if desired, and a field is specified */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $variable = null,
        /** @var array<StructureMapTargetListModeType> listMode first | share | last | collate */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true)]
        public array $listMode = [],
        /** @var IdPrimitive|null listRuleId Internal rule reference for shared list items */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive')]
        public ?IdPrimitive $listRuleId = null,
        /** @var StructureMapTransformType|null transform create | copy + */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?StructureMapTransformType $transform = null,
        /** @var array<StructureMapGroupRuleTargetParameter> parameter Parameters to the transform */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureMap\StructureMapGroupRuleTargetParameter',
        )]
        public array $parameter = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
