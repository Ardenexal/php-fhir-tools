<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Population;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductUndesirableEffect
 *
 * @description Describe the undesirable effects of the medicinal product.
 */
#[FhirResource(
    type: 'MedicinalProductUndesirableEffect',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductUndesirableEffect',
    fhirVersion: 'R4',
)]
class MedicinalProductUndesirableEffectResource extends DomainResourceResource
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
        /** @var array<Reference> subject The medication for which this is an indication */
        public array $subject = [],
        /** @var CodeableConcept|null symptomConditionEffect The symptom, condition or undesirable effect */
        public ?CodeableConcept $symptomConditionEffect = null,
        /** @var CodeableConcept|null classification Classification of the effect */
        public ?CodeableConcept $classification = null,
        /** @var CodeableConcept|null frequencyOfOccurrence The frequency of occurrence of the effect */
        public ?CodeableConcept $frequencyOfOccurrence = null,
        /** @var array<Population> population The population group to which this applies */
        public array $population = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
