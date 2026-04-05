<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SubstanceAmount;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Todo.
 */
#[FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.repeat.repeatUnit', fhirVersion: 'R4')]
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
        /** @var CodeableConcept|null orientationOfPolymerisation Todo */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $orientationOfPolymerisation = null,
        /** @var StringPrimitive|string|null repeatUnit Todo */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $repeatUnit = null,
        /** @var SubstanceAmount|null amount Todo */
        #[FhirProperty(fhirType: 'SubstanceAmount', propertyKind: 'complex')]
        public ?SubstanceAmount $amount = null,
        /** @var array<SubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation> degreeOfPolymerisation Todo */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer\SubstancePolymerRepeatRepeatUnitDegreeOfPolymerisation',
        )]
        public array $degreeOfPolymerisation = [],
        /** @var array<SubstancePolymerRepeatRepeatUnitStructuralRepresentation> structuralRepresentation Todo */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer\SubstancePolymerRepeatRepeatUnitStructuralRepresentation',
        )]
        public array $structuralRepresentation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
