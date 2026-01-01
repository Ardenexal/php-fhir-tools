<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;

/**
 * @description A description of a component of the overall certainty.
 */
#[FHIRBackboneElement(
    parentResource: 'EffectEvidenceSynthesis',
    elementPath: 'EffectEvidenceSynthesis.certainty.certaintySubcomponent',
    fhirVersion: 'R4B',
)]
class FHIREffectEvidenceSynthesisCertaintyCertaintySubcomponent extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Type of subcomponent of certainty rating */
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> rating Subcomponent certainty rating */
        public array $rating = [],
        /** @var array<FHIRAnnotation> note Used for footnotes or explanatory notes */
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
