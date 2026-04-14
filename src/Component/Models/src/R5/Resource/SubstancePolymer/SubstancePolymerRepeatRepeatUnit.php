<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstancePolymer;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @description An SRU - Structural Repeat Unit.
 */
#[FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.repeat.repeatUnit', fhirVersion: 'R5')]
class SubstancePolymerRepeatRepeatUnit extends BackboneElement
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
        /** @var StringPrimitive|string|null unit Structural repeat units are essential elements for defining polymers */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $unit = null,
        /** @var CodeableConcept|null orientation The orientation of the polymerisation, e.g. head-tail, head-head, random */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $orientation = null,
        /** @var int|null amount Number of repeats of this unit */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $amount = null,
        /** @var array<SubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation> degreeOfPolymerisation Applies to homopolymer and block co-polymers where the degree of polymerisation within a block can be described */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstancePolymer\SubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation',
        )]
        public array $degreeOfPolymerisation = [],
        /** @var array<SubstancePolymerRepeatRepeatUnitStructuralRepresentation> structuralRepresentation A graphical structure for this SRU */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstancePolymer\SubstancePolymerRepeatRepeatUnitStructuralRepresentation',
        )]
        public array $structuralRepresentation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
