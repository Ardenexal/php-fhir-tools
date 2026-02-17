<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Provenance\ProvenanceAgent;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Provenance\ProvenanceEntity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Security)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Provenance
 *
 * @description Provenance of a resource is a record that describes entities and processes involved in producing and delivering or otherwise influencing that resource. Provenance provides a critical foundation for assessing authenticity, enabling trust, and allowing reproducibility. Provenance assertions are a form of contextual metadata and can themselves become important records with their own provenance. Provenance statement indicates clinical significance in terms of confidence in authenticity, reliability, and trustworthiness, integrity, and stage in lifecycle (e.g. Document Completion - has the artifact been legally authenticated), all of which may impact security, privacy, and trust policies.
 */
#[FhirResource(type: 'Provenance', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Provenance', fhirVersion: 'R4')]
class ProvenanceResource extends DomainResourceResource
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
        /** @var array<Reference> target Target Reference(s) (usually version specific) */
        public array $target = [],
        /** @var Period|DateTimePrimitive|null occurredX When the activity occurred */
        public Period|DateTimePrimitive|null $occurredX = null,
        /** @var InstantPrimitive|null recorded When the activity was recorded / updated */
        #[NotBlank]
        public ?InstantPrimitive $recorded = null,
        /** @var array<UriPrimitive> policy Policy or plan the activity was defined by */
        public array $policy = [],
        /** @var Reference|null location Where the activity occurred, if relevant */
        public ?Reference $location = null,
        /** @var array<CodeableConcept> reason Reason the activity is occurring */
        public array $reason = [],
        /** @var CodeableConcept|null activity Activity that occurred */
        public ?CodeableConcept $activity = null,
        /** @var array<ProvenanceAgent> agent Actor involved */
        public array $agent = [],
        /** @var array<ProvenanceEntity> entity An entity used in this activity */
        public array $entity = [],
        /** @var array<Signature> signature Signature on target */
        public array $signature = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
