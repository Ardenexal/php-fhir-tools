<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A species specific time during which consumption of animal product is not appropriate.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'MedicinalProductPharmaceutical',
    elementPath: 'MedicinalProductPharmaceutical.routeOfAdministration.targetSpecies.withdrawalPeriod',
    fhirVersion: 'R4',
)]
class FHIRMedicinalProductPharmaceuticalRouteOfAdministrationTargetSpeciesWithdrawalPeriod extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null tissue Coded expression for the type of tissue for which the withdrawal period applues, e.g. meat, milk */
        #[NotBlank]
        public ?FHIRCodeableConcept $tissue = null,
        /** @var FHIRQuantity|null value A value for the time */
        #[NotBlank]
        public ?FHIRQuantity $value = null,
        /** @var FHIRString|string|null supportingInformation Extra information about the withdrawal period */
        public FHIRString|string|null $supportingInformation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
