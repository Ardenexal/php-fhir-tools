<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\AdministrableProductDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A species specific time during which consumption of animal product is not appropriate.
 */
#[FHIRBackboneElement(
    parentResource: 'AdministrableProductDefinition',
    elementPath: 'AdministrableProductDefinition.routeOfAdministration.targetSpecies.withdrawalPeriod',
    fhirVersion: 'R5',
)]
class AdministrableProductDefinitionRouteOfAdministrationTargetSpeciesWithdrawalPeriod extends BackboneElement
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
        /** @var CodeableConcept|null tissue The type of tissue for which the withdrawal period applies, e.g. meat, milk */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $tissue = null,
        /** @var Quantity|null value A value for the time */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Quantity $value = null,
        /** @var StringPrimitive|string|null supportingInformation Extra information about the withdrawal period */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $supportingInformation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
