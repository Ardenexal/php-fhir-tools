<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductUndesirableEffect
 *
 * @description Describe the undesirable effects of the medicinal product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'MedicinalProductUndesirableEffect',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductUndesirableEffect',
    fhirVersion: 'R4',
)]
class FHIRMedicinalProductUndesirableEffect extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRReference> subject The medication for which this is an indication */
        public array $subject = [],
        /** @var FHIRCodeableConcept|null symptomConditionEffect The symptom, condition or undesirable effect */
        public ?\FHIRCodeableConcept $symptomConditionEffect = null,
        /** @var FHIRCodeableConcept|null classification Classification of the effect */
        public ?\FHIRCodeableConcept $classification = null,
        /** @var FHIRCodeableConcept|null frequencyOfOccurrence The frequency of occurrence of the effect */
        public ?\FHIRCodeableConcept $frequencyOfOccurrence = null,
        /** @var array<FHIRPopulation> population The population group to which this applies */
        public array $population = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
