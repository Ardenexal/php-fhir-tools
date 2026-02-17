<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductInteraction\MedicinalProductInteractionInteractant;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductInteraction
 *
 * @description The interactions of the medicinal product with other medicinal products, or other forms of interactions.
 */
#[FhirResource(
    type: 'MedicinalProductInteraction',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductInteraction',
    fhirVersion: 'R4',
)]
class MedicinalProductInteractionResource extends DomainResourceResource
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
        /** @var array<Reference> subject The medication for which this is a described interaction */
        public array $subject = [],
        /** @var StringPrimitive|string|null description The interaction described */
        public StringPrimitive|string|null $description = null,
        /** @var array<MedicinalProductInteractionInteractant> interactant The specific medication, food or laboratory test that interacts */
        public array $interactant = [],
        /** @var CodeableConcept|null type The type of the interaction e.g. drug-drug interaction, drug-food interaction, drug-lab test interaction */
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null effect The effect of the interaction, for example "reduced gastric absorption of primary medication" */
        public ?CodeableConcept $effect = null,
        /** @var CodeableConcept|null incidence The incidence of the interaction, e.g. theoretical, observed */
        public ?CodeableConcept $incidence = null,
        /** @var CodeableConcept|null management Actions for managing the interaction */
        public ?CodeableConcept $management = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
