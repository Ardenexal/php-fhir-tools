<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\OrientationTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\StrandTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description A sequence that is used as a reference to describe variants that are present in a sequence analyzed.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.referenceSeq', fhirVersion: 'R4')]
class MolecularSequenceReferenceSeq extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null chromosome Chromosome containing genetic finding */
        public ?CodeableConcept $chromosome = null,
        /** @var StringPrimitive|string|null genomeBuild The Genome Build used for reference, following GRCh build versions e.g. 'GRCh 37' */
        public StringPrimitive|string|null $genomeBuild = null,
        /** @var OrientationTypeType|null orientation sense | antisense */
        public ?OrientationTypeType $orientation = null,
        /** @var CodeableConcept|null referenceSeqId Reference identifier */
        public ?CodeableConcept $referenceSeqId = null,
        /** @var Reference|null referenceSeqPointer A pointer to another MolecularSequence entity as reference sequence */
        public ?Reference $referenceSeqPointer = null,
        /** @var StringPrimitive|string|null referenceSeqString A string to represent reference sequence */
        public StringPrimitive|string|null $referenceSeqString = null,
        /** @var StrandTypeType|null strand watson | crick */
        public ?StrandTypeType $strand = null,
        /** @var int|null windowStart Start position of the window on the  reference sequence */
        public ?int $windowStart = null,
        /** @var int|null windowEnd End position of the window on the reference sequence */
        public ?int $windowEnd = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
