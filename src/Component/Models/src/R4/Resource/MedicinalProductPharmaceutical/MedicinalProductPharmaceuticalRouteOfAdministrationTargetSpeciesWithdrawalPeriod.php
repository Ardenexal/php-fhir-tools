<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPharmaceutical;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A species specific time during which consumption of animal product is not appropriate.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductPharmaceutical',
    elementPath: 'MedicinalProductPharmaceutical.routeOfAdministration.targetSpecies.withdrawalPeriod',
    fhirVersion: 'R4',
)]
class MedicinalProductPharmaceuticalRouteOfAdministrationTargetSpeciesWithdrawalPeriod extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null tissue Coded expression for the type of tissue for which the withdrawal period applues, e.g. meat, milk */
        #[NotBlank]
        public ?CodeableConcept $tissue = null,
        /** @var Quantity|null value A value for the time */
        #[NotBlank]
        public ?Quantity $value = null,
        /** @var StringPrimitive|string|null supportingInformation Extra information about the withdrawal period */
        public StringPrimitive|string|null $supportingInformation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
