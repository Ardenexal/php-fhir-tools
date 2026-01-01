<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSequenceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
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
class FHIRMolecularSequence extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Unique ID for this particular sequence. This is a FHIR-defined id */
        public array $identifier = [],
        /** @var FHIRSequenceTypeType|null type aa | dna | rna */
        public ?FHIRSequenceTypeType $type = null,
        /** @var FHIRInteger|null coordinateSystem Base number of coordinate system (0 for 0-based numbering or coordinates, inclusive start, exclusive end, 1 for 1-based numbering, inclusive start, inclusive end) */
        #[NotBlank]
        public ?FHIRInteger $coordinateSystem = null,
        /** @var FHIRReference|null patient Who and/or what this is about */
        public ?FHIRReference $patient = null,
        /** @var FHIRReference|null specimen Specimen used for sequencing */
        public ?FHIRReference $specimen = null,
        /** @var FHIRReference|null device The method for sequencing */
        public ?FHIRReference $device = null,
        /** @var FHIRReference|null performer Who should be responsible for test result */
        public ?FHIRReference $performer = null,
        /** @var FHIRQuantity|null quantity The number of copies of the sequence of interest.  (RNASeq) */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRMolecularSequenceReferenceSeq|null referenceSeq A sequence used as reference */
        public ?FHIRMolecularSequenceReferenceSeq $referenceSeq = null,
        /** @var array<FHIRMolecularSequenceVariant> variant Variant in sequence */
        public array $variant = [],
        /** @var FHIRString|string|null observedSeq Sequence that was observed */
        public FHIRString|string|null $observedSeq = null,
        /** @var array<FHIRMolecularSequenceQuality> quality An set of value as quality of sequence */
        public array $quality = [],
        /** @var FHIRInteger|null readCoverage Average number of reads representing a given nucleotide in the reconstructed sequence */
        public ?FHIRInteger $readCoverage = null,
        /** @var array<FHIRMolecularSequenceRepository> repository External repository which contains detailed report related with observedSeq in this resource */
        public array $repository = [],
        /** @var array<FHIRReference> pointer Pointer to next atomic sequence */
        public array $pointer = [],
        /** @var array<FHIRMolecularSequenceStructureVariant> structureVariant Structural variant */
        public array $structureVariant = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
