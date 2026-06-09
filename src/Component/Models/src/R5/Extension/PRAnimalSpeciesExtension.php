<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/practitioner-animalSpecies
 *
 * @description This extension should be used to specifiy that a practitioner or RelatedPerson resource is a service animal.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/practitioner-animalSpecies', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'Practitioner')]
#[FHIRExtensionContext(type: 'element', expression: 'RelatedPerson')]
class PRAnimalSpeciesExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var CodeableConcept|null valueCodeableConcept Value of extension */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $valueCodeableConcept = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/practitioner-animalSpecies',
            value: $this->valueCodeableConcept,
        );
    }
}
