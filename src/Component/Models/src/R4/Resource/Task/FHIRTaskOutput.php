<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Outputs produced by the Task.
 */
#[FHIRBackboneElement(parentResource: 'Task', elementPath: 'Task.output', fhirVersion: 'R4')]
class FHIRTaskOutput extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Label for output */
        #[NotBlank]
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|FHIRMeta|null valueX Result of output */
        #[NotBlank]
        public \FHIRBase64Binary|\FHIRBoolean|\FHIRCanonical|\FHIRCode|\FHIRDate|\FHIRDateTime|\FHIRDecimal|\FHIRId|\FHIRInstant|\FHIRInteger|\FHIRMarkdown|\FHIROid|\FHIRPositiveInt|\FHIRString|string|\FHIRTime|\FHIRUnsignedInt|\FHIRUri|\FHIRUrl|\FHIRUuid|\FHIRAddress|\FHIRAge|\FHIRAnnotation|\FHIRAttachment|\FHIRCodeableConcept|\FHIRCoding|\FHIRContactPoint|\FHIRCount|\FHIRDistance|\FHIRDuration|\FHIRHumanName|\FHIRIdentifier|\FHIRMoney|\FHIRPeriod|\FHIRQuantity|\FHIRRange|\FHIRRatio|\FHIRReference|\FHIRSampledData|\FHIRSignature|\FHIRTiming|\FHIRContactDetail|\FHIRContributor|\FHIRDataRequirement|\FHIRExpression|\FHIRParameterDefinition|\FHIRRelatedArtifact|\FHIRTriggerDefinition|\FHIRUsageContext|\FHIRDosage|\FHIRMeta|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
