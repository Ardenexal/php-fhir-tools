<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPharmaceutical;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The path by which the pharmaceutical product is taken into or makes contact with the body.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductPharmaceutical',
    elementPath: 'MedicinalProductPharmaceutical.routeOfAdministration',
    fhirVersion: 'R4',
)]
class MedicinalProductPharmaceuticalRouteOfAdministration extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Coded expression for the route */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $code = null,
        /** @var Quantity|null firstDose The first dose (dose quantity) administered in humans can be specified, for a product under investigation, using a numerical value and its unit of measurement */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $firstDose = null,
        /** @var Quantity|null maxSingleDose The maximum single dose that can be administered as per the protocol of a clinical trial can be specified using a numerical value and its unit of measurement */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $maxSingleDose = null,
        /** @var Quantity|null maxDosePerDay The maximum dose per day (maximum dose quantity to be administered in any one 24-h period) that can be administered as per the protocol referenced in the clinical trial authorisation */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $maxDosePerDay = null,
        /** @var Ratio|null maxDosePerTreatmentPeriod The maximum dose per treatment period that can be administered as per the protocol referenced in the clinical trial authorisation */
        #[FhirProperty(fhirType: 'Ratio', propertyKind: 'complex')]
        public ?Ratio $maxDosePerTreatmentPeriod = null,
        /** @var Duration|null maxTreatmentPeriod The maximum treatment period during which an Investigational Medicinal Product can be administered as per the protocol referenced in the clinical trial authorisation */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $maxTreatmentPeriod = null,
        /** @var array<MedicinalProductPharmaceuticalRouteOfAdministrationTargetSpecies> targetSpecies A species for which this route applies */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPharmaceutical\MedicinalProductPharmaceuticalRouteOfAdministrationTargetSpecies',
        )]
        public array $targetSpecies = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
