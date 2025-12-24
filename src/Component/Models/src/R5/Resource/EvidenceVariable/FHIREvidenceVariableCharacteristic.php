<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;

/**
 * @description A defining factor of the EvidenceVariable. Multiple characteristics are applied with "and" semantics.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'EvidenceVariable', elementPath: 'EvidenceVariable.characteristic', fhirVersion: 'R5')]
class FHIREvidenceVariableCharacteristic extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null linkId Label for internal linking */
        public ?FHIRId $linkId = null,
        /** @var FHIRMarkdown|null description Natural language description of the characteristic */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRAnnotation> note Used for footnotes or explanatory notes */
        public array $note = [],
        /** @var FHIRBoolean|null exclude Whether the characteristic is an inclusion criterion or exclusion criterion */
        public ?FHIRBoolean $exclude = null,
        /** @var FHIRReference|null definitionReference Defines the characteristic (without using type and value) by a Reference */
        public ?FHIRReference $definitionReference = null,
        /** @var FHIRCanonical|null definitionCanonical Defines the characteristic (without using type and value) by a Canonical */
        public ?FHIRCanonical $definitionCanonical = null,
        /** @var FHIRCodeableConcept|null definitionCodeableConcept Defines the characteristic (without using type and value) by a CodeableConcept */
        public ?FHIRCodeableConcept $definitionCodeableConcept = null,
        /** @var FHIRExpression|null definitionExpression Defines the characteristic (without using type and value) by an expression */
        public ?FHIRExpression $definitionExpression = null,
        /** @var FHIRId|null definitionId Defines the characteristic (without using type and value) by an id */
        public ?FHIRId $definitionId = null,
        /** @var FHIREvidenceVariableCharacteristicDefinitionByTypeAndValue|null definitionByTypeAndValue Defines the characteristic using type and value */
        public ?FHIREvidenceVariableCharacteristicDefinitionByTypeAndValue $definitionByTypeAndValue = null,
        /** @var FHIREvidenceVariableCharacteristicDefinitionByCombination|null definitionByCombination Used to specify how two or more characteristics are combined */
        public ?FHIREvidenceVariableCharacteristicDefinitionByCombination $definitionByCombination = null,
        /** @var FHIRQuantity|FHIRRange|null instancesX Number of occurrences meeting the characteristic */
        public FHIRQuantity|FHIRRange|null $instancesX = null,
        /** @var FHIRQuantity|FHIRRange|null durationX Length of time in which the characteristic is met */
        public FHIRQuantity|FHIRRange|null $durationX = null,
        /** @var array<FHIREvidenceVariableCharacteristicTimeFromEvent> timeFromEvent Timing in which the characteristic is determined */
        public array $timeFromEvent = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
