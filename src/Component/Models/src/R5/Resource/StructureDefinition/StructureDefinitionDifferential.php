<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ElementDefinition;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Symfony\Component\Validator\Constraints\Count;

/**
 * @description A differential view is expressed relative to the base StructureDefinition - a statement of differences that it applies.
 */
#[FHIRBackboneElement(parentResource: 'StructureDefinition', elementPath: 'StructureDefinition.differential', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'sdf-20',
    severity: 'error',
    expression: 'element.where(path.contains(\'.\').not()).slicing.empty()',
    human: 'No slicing on the root element',
)]
#[FHIRPathInvariant(
    key: 'sdf-8a',
    severity: 'error',
    expression: '(%resource.kind = \'logical\' or element.first().path.startsWith(%resource.type)) and (element.tail().empty() or  element.tail().all(path.startsWith(%resource.differential.element.first().path.replaceMatches(\'\\\..*\',\'\')&\'.\')))',
    human: 'In any differential, all the elements must start with the StructureDefinition\'s specified type for non-logical models, or with the same type name for logical models',
)]
class StructureDefinitionDifferential extends BackboneElement
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
        /** @var array<ElementDefinition> element Definition of elements in the resource (if no StructureDefinition) */
        #[FhirProperty(
            fhirType: 'ElementDefinition',
            propertyKind: 'complex',
            isArray: true,
            isRequired: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ElementDefinition',
        )]
        #[Count(min: 1)]
        public array $element = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
