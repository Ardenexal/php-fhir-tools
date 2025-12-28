<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A characteristic that defines the members of the evidence element. Multiple characteristics are applied with "and" semantics.
 */
#[FHIRBackboneElement(parentResource: 'EvidenceVariable', elementPath: 'EvidenceVariable.characteristic', fhirVersion: 'R4B')]
class FHIREvidenceVariableCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Natural language description of the characteristic */
        public \FHIRString|string|null $description = null,
        /** @var FHIRReference|FHIRCanonical|FHIRCodeableConcept|FHIRExpression|null definitionX What code or expression defines members? */
        #[NotBlank]
        public \FHIRReference|\FHIRCanonical|\FHIRCodeableConcept|\FHIRExpression|null $definitionX = null,
        /** @var FHIRCodeableConcept|null method Method used for describing characteristic */
        public ?\FHIRCodeableConcept $method = null,
        /** @var FHIRReference|null device Device used for determining characteristic */
        public ?\FHIRReference $device = null,
        /** @var FHIRBoolean|null exclude Whether the characteristic includes or excludes members */
        public ?\FHIRBoolean $exclude = null,
        /** @var FHIREvidenceVariableCharacteristicTimeFromStart|null timeFromStart Observation time from study start */
        public ?\FHIREvidenceVariableCharacteristicTimeFromStart $timeFromStart = null,
        /** @var FHIRGroupMeasureType|null groupMeasure mean | median | mean-of-mean | mean-of-median | median-of-mean | median-of-median */
        public ?\FHIRGroupMeasureType $groupMeasure = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
