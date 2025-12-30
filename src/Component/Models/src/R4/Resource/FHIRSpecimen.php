<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSpecimenStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Specimen
 *
 * @description A sample to be used for analysis.
 */
#[FhirResource(type: 'Specimen', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Specimen', fhirVersion: 'R4')]
class FHIRSpecimen extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier External Identifier */
        public array $identifier = [],
        /** @var FHIRIdentifier|null accessionIdentifier Identifier assigned by the lab */
        public ?FHIRIdentifier $accessionIdentifier = null,
        /** @var FHIRSpecimenStatusType|null status available | unavailable | unsatisfactory | entered-in-error */
        public ?FHIRSpecimenStatusType $status = null,
        /** @var FHIRCodeableConcept|null type Kind of material that forms the specimen */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRReference|null subject Where the specimen came from. This may be from patient(s), from a location (e.g., the source of an environmental sample), or a sampling of a substance or a device */
        public ?FHIRReference $subject = null,
        /** @var FHIRDateTime|null receivedTime The time when specimen was received for processing */
        public ?FHIRDateTime $receivedTime = null,
        /** @var array<FHIRReference> parent Specimen from which this specimen originated */
        public array $parent = [],
        /** @var array<FHIRReference> request Why the specimen was collected */
        public array $request = [],
        /** @var FHIRSpecimenCollection|null collection Collection details */
        public ?FHIRSpecimenCollection $collection = null,
        /** @var array<FHIRSpecimenProcessing> processing Processing and processing step details */
        public array $processing = [],
        /** @var array<FHIRSpecimenContainer> container Direct container of specimen (tube/slide, etc.) */
        public array $container = [],
        /** @var array<FHIRCodeableConcept> condition State of the specimen */
        public array $condition = [],
        /** @var array<FHIRAnnotation> note Comments */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
