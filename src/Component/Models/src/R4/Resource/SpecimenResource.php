<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SpecimenStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Specimen\SpecimenCollection;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Specimen\SpecimenContainer;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Specimen\SpecimenProcessing;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Specimen
 *
 * @description A sample to be used for analysis.
 */
#[FhirResource(type: 'Specimen', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Specimen', fhirVersion: 'R4')]
class SpecimenResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External Identifier */
        public array $identifier = [],
        /** @var Identifier|null accessionIdentifier Identifier assigned by the lab */
        public ?Identifier $accessionIdentifier = null,
        /** @var SpecimenStatusType|null status available | unavailable | unsatisfactory | entered-in-error */
        public ?SpecimenStatusType $status = null,
        /** @var CodeableConcept|null type Kind of material that forms the specimen */
        public ?CodeableConcept $type = null,
        /** @var Reference|null subject Where the specimen came from. This may be from patient(s), from a location (e.g., the source of an environmental sample), or a sampling of a substance or a device */
        public ?Reference $subject = null,
        /** @var DateTimePrimitive|null receivedTime The time when specimen was received for processing */
        public ?DateTimePrimitive $receivedTime = null,
        /** @var array<Reference> parent Specimen from which this specimen originated */
        public array $parent = [],
        /** @var array<Reference> request Why the specimen was collected */
        public array $request = [],
        /** @var SpecimenCollection|null collection Collection details */
        public ?SpecimenCollection $collection = null,
        /** @var array<SpecimenProcessing> processing Processing and processing step details */
        public array $processing = [],
        /** @var array<SpecimenContainer> container Direct container of specimen (tube/slide, etc.) */
        public array $container = [],
        /** @var array<CodeableConcept> condition State of the specimen */
        public array $condition = [],
        /** @var array<Annotation> note Comments */
        public array $note = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
