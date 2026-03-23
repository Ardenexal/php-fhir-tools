<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPharmaceutical;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
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
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Coded expression for the species */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $code = null,
        /** @var array<MedicinalProductPharmaceuticalRouteOfAdministrationTargetSpeciesWithdrawalPeriod> withdrawalPeriod A species specific time during which consumption of animal product is not appropriate */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPharmaceutical\MedicinalProductPharmaceuticalRouteOfAdministrationTargetSpeciesWithdrawalPeriod',
        )]
        public array $withdrawalPeriod = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
