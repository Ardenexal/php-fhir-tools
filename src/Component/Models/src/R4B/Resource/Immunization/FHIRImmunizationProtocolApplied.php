<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The protocol (set of recommendations) being followed by the provider who administered the dose.
 */
#[FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.protocolApplied', fhirVersion: 'R4B')]
class FHIRImmunizationProtocolApplied extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null series Name of vaccine series */
        public FHIRString|string|null $series = null,
        /** @var FHIRReference|null authority Who is responsible for publishing the recommendations */
        public ?FHIRReference $authority = null,
        /** @var array<FHIRCodeableConcept> targetDisease Vaccine preventatable disease being targetted */
        public array $targetDisease = [],
        /** @var FHIRPositiveInt|FHIRString|string|null doseNumberX Dose number within series */
        #[NotBlank]
        public FHIRPositiveInt|FHIRString|string|null $doseNumberX = null,
        /** @var FHIRPositiveInt|FHIRString|string|null seriesDosesX Recommended number of doses for immunity */
        public FHIRPositiveInt|FHIRString|string|null $seriesDosesX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
