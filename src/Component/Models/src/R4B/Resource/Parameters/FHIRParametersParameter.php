<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAddress;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAge;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContactPoint;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContributor;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCount;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDistance;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDosage;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExpression;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRHumanName;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMoney;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRParameterDefinition;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSampledData;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRSignature;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTiming;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTriggerDefinition;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRUsageContext;
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
 * @description A parameter passed to or received from the operation.
 */
#[FHIRBackboneElement(parentResource: 'Parameters', elementPath: 'Parameters.parameter', fhirVersion: 'R4B')]
class FHIRParametersParameter extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Name from the definition */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|FHIRMeta|null valueX If parameter is a data type */
        public FHIRBase64Binary|FHIRBoolean|FHIRCanonical|FHIRCode|FHIRDate|FHIRDateTime|FHIRDecimal|FHIRId|FHIRInstant|FHIRInteger|FHIRMarkdown|FHIROid|FHIRPositiveInt|FHIRString|string|FHIRTime|FHIRUnsignedInt|FHIRUri|FHIRUrl|FHIRUuid|FHIRAddress|FHIRAge|FHIRAnnotation|FHIRAttachment|FHIRCodeableConcept|FHIRCoding|FHIRContactPoint|FHIRCount|FHIRDistance|FHIRDuration|FHIRHumanName|FHIRIdentifier|FHIRMoney|FHIRPeriod|FHIRQuantity|FHIRRange|FHIRRatio|FHIRReference|FHIRSampledData|FHIRSignature|FHIRTiming|FHIRContactDetail|FHIRContributor|FHIRDataRequirement|FHIRExpression|FHIRParameterDefinition|FHIRRelatedArtifact|FHIRTriggerDefinition|FHIRUsageContext|FHIRDosage|FHIRMeta|null $valueX = null,
        /** @var FHIRResource|null resource If parameter is a whole resource */
        public ?FHIRResource $resource = null,
        /** @var array<FHIRParametersParameter> part Named part of a multi-part parameter */
        public array $part = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
