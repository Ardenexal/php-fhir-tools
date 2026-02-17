<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\VisionPrescription\VisionPrescriptionLensSpecification;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Financial Management)
 *
 * @see http://hl7.org/fhir/StructureDefinition/VisionPrescription
 *
 * @description An authorization for the provision of glasses and/or contact lenses to a patient.
 */
#[FhirResource(
    type: 'VisionPrescription',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/VisionPrescription',
    fhirVersion: 'R4',
)]
class VisionPrescriptionResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier Business Identifier for vision prescription */
        public array $identifier = [],
        /** @var FinancialResourceStatusCodesType|null status active | cancelled | draft | entered-in-error */
        #[NotBlank]
        public ?FinancialResourceStatusCodesType $status = null,
        /** @var DateTimePrimitive|null created Response creation date */
        #[NotBlank]
        public ?DateTimePrimitive $created = null,
        /** @var Reference|null patient Who prescription is for */
        #[NotBlank]
        public ?Reference $patient = null,
        /** @var Reference|null encounter Created during encounter / admission / stay */
        public ?Reference $encounter = null,
        /** @var DateTimePrimitive|null dateWritten When prescription was authorized */
        #[NotBlank]
        public ?DateTimePrimitive $dateWritten = null,
        /** @var Reference|null prescriber Who authorized the vision prescription */
        #[NotBlank]
        public ?Reference $prescriber = null,
        /** @var array<VisionPrescriptionLensSpecification> lensSpecification Vision lens authorization */
        public array $lensSpecification = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
