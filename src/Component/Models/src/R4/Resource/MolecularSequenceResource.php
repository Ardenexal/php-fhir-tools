<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SequenceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceQuality;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceReferenceSeq;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceRepository;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceStructureVariant;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceVariant;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MolecularSequence
 *
 * @description Raw data describing a biological sequence.
 */
#[FhirResource(
    type: 'MolecularSequence',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MolecularSequence',
    fhirVersion: 'R4',
)]
class MolecularSequenceResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Unique ID for this particular sequence. This is a FHIR-defined id */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var SequenceTypeType|null type aa | dna | rna */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?SequenceTypeType $type = null,
        /** @var int|null coordinateSystem Base number of coordinate system (0 for 0-based numbering or coordinates, inclusive start, exclusive end, 1 for 1-based numbering, inclusive start, inclusive end) */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?int $coordinateSystem = null,
        /** @var Reference|null patient Who and/or what this is about */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $patient = null,
        /** @var Reference|null specimen Specimen used for sequencing */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $specimen = null,
        /** @var Reference|null device The method for sequencing */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $device = null,
        /** @var Reference|null performer Who should be responsible for test result */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $performer = null,
        /** @var Quantity|null quantity The number of copies of the sequence of interest.  (RNASeq) */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $quantity = null,
        /** @var MolecularSequenceReferenceSeq|null referenceSeq A sequence used as reference */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?MolecularSequenceReferenceSeq $referenceSeq = null,
        /** @var array<MolecularSequenceVariant> variant Variant in sequence */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceVariant',
        )]
        public array $variant = [],
        /** @var StringPrimitive|string|null observedSeq Sequence that was observed */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $observedSeq = null,
        /** @var array<MolecularSequenceQuality> quality An set of value as quality of sequence */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceQuality',
        )]
        public array $quality = [],
        /** @var int|null readCoverage Average number of reads representing a given nucleotide in the reconstructed sequence */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $readCoverage = null,
        /** @var array<MolecularSequenceRepository> repository External repository which contains detailed report related with observedSeq in this resource */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceRepository',
        )]
        public array $repository = [],
        /** @var array<Reference> pointer Pointer to next atomic sequence */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
        )]
        public array $pointer = [],
        /** @var array<MolecularSequenceStructureVariant> structureVariant Structural variant */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence\MolecularSequenceStructureVariant',
        )]
        public array $structureVariant = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
