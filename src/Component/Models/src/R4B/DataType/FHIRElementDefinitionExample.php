<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBase64Binary;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInstant;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIROid;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUnsignedInt;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUrl;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUuid;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A sample value for this element demonstrating the type of information that would typically be found in the element.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.example', fhirVersion: 'R4B')]
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
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|null valueX Value of Example (one of allowed types) */
        #[NotBlank]
        public FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCodeableReference|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRRatioRange|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|null $valueX = null,
    ) {
        parent::__construct($id, $extension);
    }
}
