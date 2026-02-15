<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPharmaceutical;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A species for which this route applies.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductPharmaceutical',
    elementPath: 'MedicinalProductPharmaceutical.routeOfAdministration.targetSpecies',
    fhirVersion: 'R4',
)]
class MedicinalProductPharmaceuticalRouteOfAdministrationTargetSpecies extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Coded expression for the species */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var array<MedicinalProductPharmaceuticalRouteOfAdministrationTargetSpeciesWithdrawalPeriod> withdrawalPeriod A species specific time during which consumption of animal product is not appropriate */
        public array $withdrawalPeriod = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
