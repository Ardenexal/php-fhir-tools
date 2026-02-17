<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DataRequirement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\GuidanceResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
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
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/GuidanceResponse',
    fhirVersion: 'R4',
)]
class GuidanceResponseResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var Identifier|null requestIdentifier The identifier of the request associated with this response, if any */
        public ?Identifier $requestIdentifier = null,
        /** @var array<Identifier> identifier Business identifier */
        public array $identifier = [],
        /** @var UriPrimitive|CanonicalPrimitive|CodeableConcept|null moduleX What guidance was requested */
        #[NotBlank]
        public UriPrimitive|CanonicalPrimitive|CodeableConcept|null $moduleX = null,
        /** @var GuidanceResponseStatusType|null status success | data-requested | data-required | in-progress | failure | entered-in-error */
        #[NotBlank]
        public ?GuidanceResponseStatusType $status = null,
        /** @var Reference|null subject Patient the request was performed for */
        public ?Reference $subject = null,
        /** @var Reference|null encounter Encounter during which the response was returned */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|null occurrenceDateTime When the guidance response was processed */
        public ?DateTimePrimitive $occurrenceDateTime = null,
        /** @var Reference|null performer Device returning the guidance */
        public ?Reference $performer = null,
        /** @var array<CodeableConcept> reasonCode Why guidance is needed */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why guidance is needed */
        public array $reasonReference = [],
        /** @var array<Annotation> note Additional notes about the response */
        public array $note = [],
        /** @var array<Reference> evaluationMessage Messages resulting from the evaluation of the artifact or artifacts */
        public array $evaluationMessage = [],
        /** @var Reference|null outputParameters The output parameters of the evaluation, if any */
        public ?Reference $outputParameters = null,
        /** @var Reference|null result Proposed actions, if any */
        public ?Reference $result = null,
        /** @var array<DataRequirement> dataRequirement Additional required data */
        public array $dataRequirement = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
