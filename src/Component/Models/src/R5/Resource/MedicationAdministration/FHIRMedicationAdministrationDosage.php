<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @description Describes the medication dosage information details e.g. dose, rate, site, route, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationAdministration', elementPath: 'MedicationAdministration.dosage', fhirVersion: 'R5')]
class FHIRMedicationAdministrationDosage extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null text Free text dosage instructions e.g. SIG */
        public FHIRString|string|null $text = null,
        /** @var FHIRCodeableConcept|null site Body site administered to */
        public ?FHIRCodeableConcept $site = null,
        /** @var FHIRCodeableConcept|null route Path of substance into body */
        public ?FHIRCodeableConcept $route = null,
        /** @var FHIRCodeableConcept|null method How drug was administered */
        public ?FHIRCodeableConcept $method = null,
        /** @var FHIRQuantity|null dose Amount of medication per dose */
        public ?FHIRQuantity $dose = null,
        /** @var FHIRRatio|FHIRQuantity|null rateX Dose quantity per unit of time */
        public FHIRRatio|FHIRQuantity|null $rateX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
