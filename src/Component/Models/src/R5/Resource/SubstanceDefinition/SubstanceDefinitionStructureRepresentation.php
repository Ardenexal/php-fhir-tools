<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @description A depiction of the structure of the substance.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceDefinition', elementPath: 'SubstanceDefinition.structure.representation', fhirVersion: 'R5')]
class SubstanceDefinitionStructureRepresentation extends BackboneElement
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
        /** @var CodeableConcept|null type The kind of structural representation (e.g. full, partial) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        /** @var StringPrimitive|string|null representation The structural representation as a text string in a standard format */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $representation = null,
        /** @var CodeableConcept|null format The format of the representation e.g. InChI, SMILES, MOLFILE (note: not the physical file format) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $format = null,
        /** @var Reference|null document An attachment with the structural representation e.g. a structure graphic or AnIML file */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $document = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
