<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstancePolymer;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Quantity;

/**
 * @description The starting materials - monomer(s) used in the synthesis of the polymer.
 */
#[FHIRBackboneElement(parentResource: 'SubstancePolymer', elementPath: 'SubstancePolymer.monomerSet.startingMaterial', fhirVersion: 'R5')]
class SubstancePolymerMonomerSetStartingMaterial extends BackboneElement
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
        /** @var CodeableConcept|null code The type of substance for this starting material */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $code = null,
        /** @var CodeableConcept|null category Substance high level category, e.g. chemical substance */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $category = null,
        /** @var bool|null isDefining Used to specify whether the attribute described is a defining element for the unique identification of the polymer */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $isDefining = null,
        /** @var Quantity|null amount A percentage */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $amount = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
