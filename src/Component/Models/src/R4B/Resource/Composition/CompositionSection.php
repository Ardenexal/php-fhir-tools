<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\Composition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ListModeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @description The root of the sections that make up the composition.
 */
#[FHIRBackboneElement(parentResource: 'Composition', elementPath: 'Composition.section', fhirVersion: 'R4B')]
#[FHIRPathInvariant(
    key: 'cmp-1',
    severity: 'error',
    expression: 'text.exists() or entry.exists() or section.exists()',
    human: 'A section must contain at least one of text, entries, or sub-sections',
)]
#[FHIRPathInvariant(
    key: 'cmp-2',
    severity: 'error',
    expression: 'emptyReason.empty() or entry.empty()',
    human: 'A section can only have an emptyReason if it is empty',
)]
class CompositionSection extends BackboneElement
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
        /** @var StringPrimitive|string|null title Label for section (e.g. for ToC) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $title = null,
        /** @var CodeableConcept|null code Classification of section (recommended) */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $code = null,
        /** @var array<Reference> author Who and/or what authored the section */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Practitioner',
            'http://hl7.org/fhir/StructureDefinition/PractitionerRole',
            'http://hl7.org/fhir/StructureDefinition/Device',
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'http://hl7.org/fhir/StructureDefinition/RelatedPerson',
            'http://hl7.org/fhir/StructureDefinition/Organization',
        ])]
        public array $author = [],
        /** @var Reference|null focus Who/what the section is about, when it is not about the subject of composition */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Resource'])]
        public ?Reference $focus = null,
        /** @var Narrative|null text Text summary of the section, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var ListModeType|null mode working | snapshot | changes */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/list-mode|4.3.0', strength: 'required')]
        public ?ListModeType $mode = null,
        /** @var CodeableConcept|null orderedBy Order of section entries */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/list-order', strength: 'preferred')]
        public ?CodeableConcept $orderedBy = null,
        /** @var array<Reference> entry A reference to data that supports this section */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Resource'])]
        public array $entry = [],
        /** @var CodeableConcept|null emptyReason Why the section is empty */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/list-empty-reason', strength: 'preferred')]
        public ?CodeableConcept $emptyReason = null,
        /** @var array<CompositionSection> section Nested Section */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Composition\CompositionSection',
        )]
        public array $section = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
