<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SpecimenDefinition\SpecimenDefinitionTypeTested;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SpecimenDefinition
 *
 * @description A kind of specimen with associated set of requirements.
 */
#[FhirResource(
    type: 'SpecimenDefinition',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/SpecimenDefinition',
    fhirVersion: 'R4',
)]
class SpecimenDefinitionResource extends DomainResourceResource
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
        /** @var Identifier|null identifier Business identifier of a kind of specimen */
        public ?Identifier $identifier = null,
        /** @var CodeableConcept|null typeCollected Kind of material to collect */
        public ?CodeableConcept $typeCollected = null,
        /** @var array<CodeableConcept> patientPreparation Patient preparation for collection */
        public array $patientPreparation = [],
        /** @var StringPrimitive|string|null timeAspect Time aspect for collection */
        public StringPrimitive|string|null $timeAspect = null,
        /** @var array<CodeableConcept> collection Specimen collection procedure */
        public array $collection = [],
        /** @var array<SpecimenDefinitionTypeTested> typeTested Specimen in container intended for testing by lab */
        public array $typeTested = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
