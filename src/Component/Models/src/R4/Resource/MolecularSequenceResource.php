<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
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
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Unique ID for this particular sequence. This is a FHIR-defined id */
        public array $identifier = [],
        /** @var SequenceTypeType|null type aa | dna | rna */
        public ?SequenceTypeType $type = null,
        /** @var int|null coordinateSystem Base number of coordinate system (0 for 0-based numbering or coordinates, inclusive start, exclusive end, 1 for 1-based numbering, inclusive start, inclusive end) */
        #[NotBlank]
        public ?int $coordinateSystem = null,
        /** @var Reference|null patient Who and/or what this is about */
        public ?Reference $patient = null,
        /** @var Reference|null specimen Specimen used for sequencing */
        public ?Reference $specimen = null,
        /** @var Reference|null device The method for sequencing */
        public ?Reference $device = null,
        /** @var Reference|null performer Who should be responsible for test result */
        public ?Reference $performer = null,
        /** @var Quantity|null quantity The number of copies of the sequence of interest.  (RNASeq) */
        public ?Quantity $quantity = null,
        /** @var MolecularSequenceReferenceSeq|null referenceSeq A sequence used as reference */
        public ?MolecularSequenceReferenceSeq $referenceSeq = null,
        /** @var array<MolecularSequenceVariant> variant Variant in sequence */
        public array $variant = [],
        /** @var StringPrimitive|string|null observedSeq Sequence that was observed */
        public StringPrimitive|string|null $observedSeq = null,
        /** @var array<MolecularSequenceQuality> quality An set of value as quality of sequence */
        public array $quality = [],
        /** @var int|null readCoverage Average number of reads representing a given nucleotide in the reconstructed sequence */
        public ?int $readCoverage = null,
        /** @var array<MolecularSequenceRepository> repository External repository which contains detailed report related with observedSeq in this resource */
        public array $repository = [],
        /** @var array<Reference> pointer Pointer to next atomic sequence */
        public array $pointer = [],
        /** @var array<MolecularSequenceStructureVariant> structureVariant Structural variant */
        public array $structureVariant = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
