<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

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
    fhirVersion: 'R5',
)]
class FHIRMedicinalProductInteraction extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRReference> subject The medication for which this is a described interaction */
        public array $subject = [],
        /** @var FHIRString|string|null description The interaction described */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRMedicinalProductInteractionInteractant> interactant The specific medication, food or laboratory test that interacts */
        public array $interactant = [],
        /** @var FHIRCodeableConcept|null type The type of the interaction e.g. drug-drug interaction, drug-food interaction, drug-lab test interaction */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null effect The effect of the interaction, for example "reduced gastric absorption of primary medication" */
        public ?FHIRCodeableConcept $effect = null,
        /** @var FHIRCodeableConcept|null incidence The incidence of the interaction, e.g. theoretical, observed */
        public ?FHIRCodeableConcept $incidence = null,
        /** @var FHIRCodeableConcept|null management Actions for managing the interaction */
        public ?FHIRCodeableConcept $management = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
