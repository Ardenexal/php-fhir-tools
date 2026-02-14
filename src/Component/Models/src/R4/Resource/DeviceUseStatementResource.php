<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\DeviceUseStatementStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Timing;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceUseStatement
 *
 * @description A record of a device being used by a patient where the record is the result of a report from the patient or another clinician.
 */
#[FhirResource(
    type: 'DeviceUseStatement',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/DeviceUseStatement',
    fhirVersion: 'R4',
)]
class DeviceUseStatementResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External identifier for this record */
        public array $identifier = [],
        /** @var array<Reference> basedOn Fulfills plan, proposal or order */
        public array $basedOn = [],
        /** @var DeviceUseStatementStatusType|null status active | completed | entered-in-error + */
        #[NotBlank]
        public ?DeviceUseStatementStatusType $status = null,
        /** @var Reference|null subject Patient using device */
        #[NotBlank]
        public ?Reference $subject = null,
        /** @var array<Reference> derivedFrom Supporting information */
        public array $derivedFrom = [],
        /** @var Timing|Period|DateTimePrimitive|null timingX How often  the device was used */
        public Timing|Period|DateTimePrimitive|null $timingX = null,
        /** @var DateTimePrimitive|null recordedOn When statement was recorded */
        public ?DateTimePrimitive $recordedOn = null,
        /** @var Reference|null source Who made the statement */
        public ?Reference $source = null,
        /** @var Reference|null device Reference to device used */
        #[NotBlank]
        public ?Reference $device = null,
        /** @var array<CodeableConcept> reasonCode Why device was used */
        public array $reasonCode = [],
        /** @var array<Reference> reasonReference Why was DeviceUseStatement performed? */
        public array $reasonReference = [],
        /** @var CodeableConcept|null bodySite Target body site */
        public ?CodeableConcept $bodySite = null,
        /** @var array<Annotation> note Addition details (comments, instructions) */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
