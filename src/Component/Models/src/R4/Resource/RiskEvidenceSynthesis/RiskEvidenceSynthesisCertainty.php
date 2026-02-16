<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\RiskEvidenceSynthesis;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description A description of the certainty of the risk estimate.
 */
#[FHIRBackboneElement(parentResource: 'RiskEvidenceSynthesis', elementPath: 'RiskEvidenceSynthesis.certainty', fhirVersion: 'R4')]
class RiskEvidenceSynthesisCertainty extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> rating Certainty rating */
        public array $rating = [],
        /** @var array<Annotation> note Used for footnotes or explanatory notes */
        public array $note = [],
        /** @var array<RiskEvidenceSynthesisCertaintyCertaintySubcomponent> certaintySubcomponent A component that contributes to the overall certainty */
        public array $certaintySubcomponent = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
