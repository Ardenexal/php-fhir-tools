<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description A sequence that is used as a reference to describe variants that are present in a sequence analyzed.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.referenceSeq', fhirVersion: 'R4')]
class FHIRMolecularSequenceReferenceSeq extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null chromosome Chromosome containing genetic finding */
        public ?FHIRCodeableConcept $chromosome = null,
        /** @var FHIRString|string|null genomeBuild The Genome Build used for reference, following GRCh build versions e.g. 'GRCh 37' */
        public FHIRString|string|null $genomeBuild = null,
        /** @var FHIROrientationTypeType|null orientation sense | antisense */
        public ?FHIROrientationTypeType $orientation = null,
        /** @var FHIRCodeableConcept|null referenceSeqId Reference identifier */
        public ?FHIRCodeableConcept $referenceSeqId = null,
        /** @var FHIRReference|null referenceSeqPointer A pointer to another MolecularSequence entity as reference sequence */
        public ?FHIRReference $referenceSeqPointer = null,
        /** @var FHIRString|string|null referenceSeqString A string to represent reference sequence */
        public FHIRString|string|null $referenceSeqString = null,
        /** @var FHIRStrandTypeType|null strand watson | crick */
        public ?FHIRStrandTypeType $strand = null,
        /** @var FHIRInteger|null windowStart Start position of the window on the  reference sequence */
        public ?FHIRInteger $windowStart = null,
        /** @var FHIRInteger|null windowEnd End position of the window on the reference sequence */
        public ?FHIRInteger $windowEnd = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
