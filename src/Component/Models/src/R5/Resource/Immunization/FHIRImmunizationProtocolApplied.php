<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The protocol (set of recommendations) being followed by the provider who administered the dose.
 */
#[FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.protocolApplied', fhirVersion: 'R5')]
class FHIRImmunizationProtocolApplied extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null series Name of vaccine series */
        public \FHIRString|string|null $series = null,
        /** @var FHIRReference|null authority Who is responsible for publishing the recommendations */
        public ?\FHIRReference $authority = null,
        /** @var array<FHIRCodeableConcept> targetDisease Vaccine preventatable disease being targeted */
        public array $targetDisease = [],
        /** @var FHIRString|string|null doseNumber Dose number within series */
        #[NotBlank]
        public \FHIRString|string|null $doseNumber = null,
        /** @var FHIRString|string|null seriesDoses Recommended number of doses for immunity */
        public \FHIRString|string|null $seriesDoses = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
