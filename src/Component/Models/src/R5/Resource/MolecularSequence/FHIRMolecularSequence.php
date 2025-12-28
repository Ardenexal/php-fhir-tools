<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MolecularSequence
 *
 * @description Representation of a molecular sequence.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'MolecularSequence',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/MolecularSequence',
    fhirVersion: 'R5',
)]
class FHIRMolecularSequence extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Unique ID for this particular sequence */
        public array $identifier = [],
        /** @var FHIRSequenceTypeType|null type aa | dna | rna */
        public ?\FHIRSequenceTypeType $type = null,
        /** @var FHIRReference|null subject Subject this sequence is associated too */
        public ?\FHIRReference $subject = null,
        /** @var array<FHIRReference> focus What the molecular sequence is about, when it is not about the subject of record */
        public array $focus = [],
        /** @var FHIRReference|null specimen Specimen used for sequencing */
        public ?\FHIRReference $specimen = null,
        /** @var FHIRReference|null device The method for sequencing */
        public ?\FHIRReference $device = null,
        /** @var FHIRReference|null performer Who should be responsible for test result */
        public ?\FHIRReference $performer = null,
        /** @var FHIRString|string|null literal Sequence that was observed */
        public \FHIRString|string|null $literal = null,
        /** @var array<FHIRAttachment> formatted Embedded file or a link (URL) which contains content to represent the sequence */
        public array $formatted = [],
        /** @var array<FHIRMolecularSequenceRelative> relative A sequence defined relative to another sequence */
        public array $relative = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
