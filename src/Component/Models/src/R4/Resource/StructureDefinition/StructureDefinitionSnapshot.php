<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ElementDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description A snapshot view is expressed in a standalone form that can be used and interpreted without considering the base StructureDefinition.
 */
#[FHIRBackboneElement(parentResource: 'StructureDefinition', elementPath: 'StructureDefinition.snapshot', fhirVersion: 'R4')]
class StructureDefinitionSnapshot extends BackboneElement
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
        /** @var array<ElementDefinition> element Definition of elements in the resource (if no StructureDefinition) */
        #[FhirProperty(
            fhirType: 'ElementDefinition',
            propertyKind: 'complex',
            isArray: true,
            isRequired: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ElementDefinition',
        )]
        public array $element = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
