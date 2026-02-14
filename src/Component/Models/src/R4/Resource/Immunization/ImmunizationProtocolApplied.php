<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The protocol (set of recommendations) being followed by the provider who administered the dose.
 */
#[FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.protocolApplied', fhirVersion: 'R4')]
class ImmunizationProtocolApplied extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null series Name of vaccine series */
        public StringPrimitive|string|null $series = null,
        /** @var Reference|null authority Who is responsible for publishing the recommendations */
        public ?Reference $authority = null,
        /** @var array<CodeableConcept> targetDisease Vaccine preventatable disease being targetted */
        public array $targetDisease = [],
        /** @var PositiveIntPrimitive|StringPrimitive|string|null doseNumberX Dose number within series */
        #[NotBlank]
        public PositiveIntPrimitive|StringPrimitive|string|null $doseNumberX = null,
        /** @var PositiveIntPrimitive|StringPrimitive|string|null seriesDosesX Recommended number of doses for immunity */
        public PositiveIntPrimitive|StringPrimitive|string|null $seriesDosesX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
