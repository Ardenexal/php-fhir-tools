<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The path by which the product is taken into or makes contact with the body. In some regions this is referred to as the licenced or approved route. RouteOfAdministration cannot be used when the 'formOf' product already uses MedicinalProductDefinition.route (and vice versa).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'AdministrableProductDefinition',
    elementPath: 'AdministrableProductDefinition.routeOfAdministration',
    fhirVersion: 'R5',
)]
class FHIRAdministrableProductDefinitionRouteOfAdministration extends FHIRBackboneElement
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
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRQuantity|null firstDose The first dose (dose quantity) administered can be specified for the product */
        public ?FHIRQuantity $firstDose = null,
        /** @var FHIRQuantity|null maxSingleDose The maximum single dose that can be administered */
        public ?FHIRQuantity $maxSingleDose = null,
        /** @var FHIRQuantity|null maxDosePerDay The maximum dose quantity to be administered in any one 24-h period */
        public ?FHIRQuantity $maxDosePerDay = null,
        /** @var FHIRRatio|null maxDosePerTreatmentPeriod The maximum dose per treatment period that can be administered */
        public ?FHIRRatio $maxDosePerTreatmentPeriod = null,
        /** @var FHIRDuration|null maxTreatmentPeriod The maximum treatment period during which the product can be administered */
        public ?FHIRDuration $maxTreatmentPeriod = null,
        /** @var array<FHIRAdministrableProductDefinitionRouteOfAdministrationTargetSpecies> targetSpecies A species for which this route applies */
        public array $targetSpecies = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
