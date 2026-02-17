<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\EffectEvidenceSynthesis;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description A description of the size of the sample involved in the synthesis.
 */
#[FHIRBackboneElement(parentResource: 'EffectEvidenceSynthesis', elementPath: 'EffectEvidenceSynthesis.sampleSize', fhirVersion: 'R4')]
class EffectEvidenceSynthesisSampleSize extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null description Description of sample size */
        public StringPrimitive|string|null $description = null,
        /** @var int|null numberOfStudies How many studies? */
        public ?int $numberOfStudies = null,
        /** @var int|null numberOfParticipants How many participants? */
        public ?int $numberOfParticipants = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
