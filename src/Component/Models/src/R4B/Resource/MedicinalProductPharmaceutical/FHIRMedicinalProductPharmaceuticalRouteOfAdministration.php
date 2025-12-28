<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The path by which the pharmaceutical product is taken into or makes contact with the body.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductPharmaceutical',
    elementPath: 'MedicinalProductPharmaceutical.routeOfAdministration',
    fhirVersion: 'R4B',
)]
class FHIRMedicinalProductPharmaceuticalRouteOfAdministration extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code Coded expression for the route */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var FHIRQuantity|null firstDose The first dose (dose quantity) administered in humans can be specified, for a product under investigation, using a numerical value and its unit of measurement */
        public ?\FHIRQuantity $firstDose = null,
        /** @var FHIRQuantity|null maxSingleDose The maximum single dose that can be administered as per the protocol of a clinical trial can be specified using a numerical value and its unit of measurement */
        public ?\FHIRQuantity $maxSingleDose = null,
        /** @var FHIRQuantity|null maxDosePerDay The maximum dose per day (maximum dose quantity to be administered in any one 24-h period) that can be administered as per the protocol referenced in the clinical trial authorisation */
        public ?\FHIRQuantity $maxDosePerDay = null,
        /** @var FHIRRatio|null maxDosePerTreatmentPeriod The maximum dose per treatment period that can be administered as per the protocol referenced in the clinical trial authorisation */
        public ?\FHIRRatio $maxDosePerTreatmentPeriod = null,
        /** @var FHIRDuration|null maxTreatmentPeriod The maximum treatment period during which an Investigational Medicinal Product can be administered as per the protocol referenced in the clinical trial authorisation */
        public ?\FHIRDuration $maxTreatmentPeriod = null,
        /** @var array<FHIRMedicinalProductPharmaceuticalRouteOfAdministrationTargetSpecies> targetSpecies A species for which this route applies */
        public array $targetSpecies = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
