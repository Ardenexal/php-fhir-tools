<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBase64Binary;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIROid;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUuid;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A sample value for this element demonstrating the type of information that would typically be found in the element.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.example', fhirVersion: 'R5')]
class FHIRElementDefinitionExample extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRString|string|null label Describes the purpose of this example */
        #[NotBlank]
        public FHIRString|string|null $label = null,
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRInteger64|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRAvailability|FHIRExtendedContactDetail|FHIRDosage|FHIRMeta|null valueX Value of Example (one of allowed types) */
        #[NotBlank]
        public FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRInteger64|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRAvailability|FHIRExtendedContactDetail|FHIRDosage|FHIRMeta|null $valueX = null,
    ) {
        parent::__construct($id, $extension);
    }
}
