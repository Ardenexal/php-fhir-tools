<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRGuidanceResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Clinical Decision Support)
 *
 * @see http://hl7.org/fhir/StructureDefinition/GuidanceResponse
 *
 * @description A guidance response is the formal response to a guidance request, including any output parameters returned by the evaluation, as well as the description of any proposed actions to be taken.
 */
#[FhirResource(
    type: 'GuidanceResponse',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/GuidanceResponse',
    fhirVersion: 'R4B',
)]
class FHIRGuidanceResponse extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null requestIdentifier The identifier of the request associated with this response, if any */
        public ?FHIRIdentifier $requestIdentifier = null,
        /** @var array<FHIRIdentifier> identifier Business identifier */
        public array $identifier = [],
        /** @var FHIRUri|FHIRCanonical|FHIRCodeableConcept|null moduleX What guidance was requested */
        #[NotBlank]
        public FHIRUri|FHIRCanonical|FHIRCodeableConcept|null $moduleX = null,
        /** @var FHIRGuidanceResponseStatusType|null status success | data-requested | data-required | in-progress | failure | entered-in-error */
        #[NotBlank]
        public ?FHIRGuidanceResponseStatusType $status = null,
        /** @var FHIRReference|null subject Patient the request was performed for */
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null encounter Encounter during which the response was returned */
        public ?FHIRReference $encounter = null,
        /** @var FHIRDateTime|null occurrenceDateTime When the guidance response was processed */
        public ?FHIRDateTime $occurrenceDateTime = null,
        /** @var FHIRReference|null performer Device returning the guidance */
        public ?FHIRReference $performer = null,
        /** @var array<FHIRCodeableConcept> reasonCode Why guidance is needed */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Why guidance is needed */
        public array $reasonReference = [],
        /** @var array<FHIRAnnotation> note Additional notes about the response */
        public array $note = [],
        /** @var array<FHIRReference> evaluationMessage Messages resulting from the evaluation of the artifact or artifacts */
        public array $evaluationMessage = [],
        /** @var FHIRReference|null outputParameters The output parameters of the evaluation, if any */
        public ?FHIRReference $outputParameters = null,
        /** @var FHIRReference|null result Proposed actions, if any */
        public ?FHIRReference $result = null,
        /** @var array<FHIRDataRequirement> dataRequirement Additional required data */
        public array $dataRequirement = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
