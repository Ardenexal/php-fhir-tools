<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DeviceAssociation
 *
 * @description A record of association or dissociation of a device with a patient.
 */
#[FhirResource(
    type: 'DeviceAssociation',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/DeviceAssociation',
    fhirVersion: 'R5',
)]
class FHIRDeviceAssociation extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Instance identifier */
        public array $identifier = [],
        /** @var FHIRReference|null device Reference to the devices associated with the patient or group */
        #[NotBlank]
        public ?FHIRReference $device = null,
        /** @var array<FHIRCodeableConcept> category Describes the relationship between the device and subject */
        public array $category = [],
        /** @var FHIRCodeableConcept|null status implanted | explanted | attached | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIRCodeableConcept $status = null,
        /** @var array<FHIRCodeableConcept> statusReason The reasons given for the current association status */
        public array $statusReason = [],
        /** @var FHIRReference|null subject The individual, group of individuals or device that the device is on or associated with */
        public ?FHIRReference $subject = null,
        /** @var FHIRReference|null bodyStructure Current anatomical location of the device in/on subject */
        public ?FHIRReference $bodyStructure = null,
        /** @var FHIRPeriod|null period Begin and end dates and times for the device association */
        public ?FHIRPeriod $period = null,
        /** @var array<FHIRDeviceAssociationOperation> operation The details about the device when it is in use to describe its operation */
        public array $operation = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
