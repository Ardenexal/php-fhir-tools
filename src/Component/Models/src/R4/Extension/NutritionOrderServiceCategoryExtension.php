<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/nutritionorder-serviceCategory
 *
 * @description Partitions the NutritionOrder into one or more service categories that can be used to filter searching, govern access control, and guide system behavior.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/nutritionorder-serviceCategory', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'NutritionOrder')]
class NutritionOrderServiceCategoryExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/nutritionorder-serviceCategory',
            value: $this->valueCodeableConcept,
        );
    }
}
