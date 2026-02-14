<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationAdministration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Describes the medication dosage information details e.g. dose, rate, site, route, etc.
 */
#[FHIRBackboneElement(parentResource: 'MedicationAdministration', elementPath: 'MedicationAdministration.dosage', fhirVersion: 'R4')]
class MedicationAdministrationDosage extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null text Free text dosage instructions e.g. SIG */
        public StringPrimitive|string|null $text = null,
        /** @var CodeableConcept|null site Body site administered to */
        public ?CodeableConcept $site = null,
        /** @var CodeableConcept|null route Path of substance into body */
        public ?CodeableConcept $route = null,
        /** @var CodeableConcept|null method How drug was administered */
        public ?CodeableConcept $method = null,
        /** @var Quantity|null dose Amount of medication per dose */
        public ?Quantity $dose = null,
        /** @var Ratio|Quantity|null rateX Dose quantity per unit of time */
        public Ratio|Quantity|null $rateX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
