<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ClinicalImpression;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Specific findings or diagnoses that were considered likely or relevant to ongoing treatment.
 */
#[FHIRBackboneElement(parentResource: 'ClinicalImpression', elementPath: 'ClinicalImpression.finding', fhirVersion: 'R4')]
class ClinicalImpressionFinding extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null itemCodeableConcept What was found */
        public ?CodeableConcept $itemCodeableConcept = null,
        /** @var Reference|null itemReference What was found */
        public ?Reference $itemReference = null,
        /** @var StringPrimitive|string|null basis Which investigations support finding */
        public StringPrimitive|string|null $basis = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
