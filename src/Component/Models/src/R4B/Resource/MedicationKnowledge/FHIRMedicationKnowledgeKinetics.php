<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;

/**
 * @description The time course of drug absorption, distribution, metabolism and excretion of a medication from the body.
 */
#[FHIRBackboneElement(parentResource: 'MedicationKnowledge', elementPath: 'MedicationKnowledge.kinetics', fhirVersion: 'R4B')]
class FHIRMedicationKnowledgeKinetics extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRQuantity> areaUnderCurve The drug concentration measured at certain discrete points in time */
        public array $areaUnderCurve = [],
        /** @var array<FHIRQuantity> lethalDose50 The median lethal dose of a drug */
        public array $lethalDose50 = [],
        /** @var FHIRDuration|null halfLifePeriod Time required for concentration in the body to decrease by half */
        public ?FHIRDuration $halfLifePeriod = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
