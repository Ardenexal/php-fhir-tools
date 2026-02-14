<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RiskEvidenceSynthesis;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description A description of a component of the overall certainty.
 */
#[FHIRBackboneElement(
    parentResource: 'RiskEvidenceSynthesis',
    elementPath: 'RiskEvidenceSynthesis.certainty.certaintySubcomponent',
    fhirVersion: 'R4',
)]
class RiskEvidenceSynthesisCertaintyCertaintySubcomponent extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Type of subcomponent of certainty rating */
        public ?CodeableConcept $type = null,
        /** @var array<CodeableConcept> rating Subcomponent certainty rating */
        public array $rating = [],
        /** @var array<Annotation> note Used for footnotes or explanatory notes */
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
