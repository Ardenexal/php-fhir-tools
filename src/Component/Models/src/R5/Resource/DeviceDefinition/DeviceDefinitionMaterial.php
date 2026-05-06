<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\DeviceDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A substance used to create the material(s) of which the device is made.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.material', fhirVersion: 'R5')]
class DeviceDefinitionMaterial extends BackboneElement
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
        /** @var CodeableConcept|null substance A relevant substance that the device contains, may contain, or is made of */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $substance = null,
        /** @var bool|null alternate Indicates an alternative material of the device */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $alternate = null,
        /** @var bool|null allergenicIndicator Whether the substance is a known or suspected allergen */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $allergenicIndicator = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
