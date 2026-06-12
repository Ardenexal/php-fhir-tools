<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\SequenceTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MolecularSequence\MolecularSequenceRelative;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MolecularSequence
 *
 * @description Representation of a molecular sequence.
 */
#[FhirResource(
    type: 'MolecularSequence',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MolecularSequence',
    fhirVersion: 'R5',
)]
class MolecularSequenceResource extends AbstractDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRIsModifier(reason: 'This element is labeled as a modifier because the implicit rules may provide additional knowledge about the resource that modifies its meaning or interpretation')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages|5.0.0', strength: 'required')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<AbstractResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the resource that contains them')]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Unique ID for this particular sequence */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var SequenceTypeType|null type aa | dna | rna */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/sequence-type|5.0.0', strength: 'required')]
        public ?SequenceTypeType $type = null,
        /** @var Reference|null subject Subject this sequence is associated too */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'http://hl7.org/fhir/StructureDefinition/Group',
            'http://hl7.org/fhir/StructureDefinition/Substance',
            'http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProduct',
            'http://hl7.org/fhir/StructureDefinition/NutritionProduct',
        ])]
        public ?Reference $subject = null,
        /** @var array<Reference> focus What the molecular sequence is about, when it is not about the subject of record */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Resource'])]
        public array $focus = [],
        /** @var Reference|null specimen Specimen used for sequencing */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Specimen'])]
        public ?Reference $specimen = null,
        /** @var Reference|null device The method for sequencing */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Device'])]
        public ?Reference $device = null,
        /** @var Reference|null performer Who should be responsible for test result */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Organization'])]
        public ?Reference $performer = null,
        /** @var StringPrimitive|string|null literal Sequence that was observed */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $literal = null,
        /** @var array<Attachment> formatted Embedded file or a link (URL) which contains content to represent the sequence */
        #[FhirProperty(
            fhirType: 'Attachment',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Attachment',
        )]
        public array $formatted = [],
        /** @var array<MolecularSequenceRelative> relative A sequence defined relative to another sequence */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\MolecularSequence\MolecularSequenceRelative',
        )]
        public array $relative = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
