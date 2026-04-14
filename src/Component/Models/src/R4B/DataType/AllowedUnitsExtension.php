<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR Core WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/elementdefinition-allowedUnits
 *
 * @description Identifies the units of measure in which the element should be captured or expressed.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-allowedUnits', fhirVersion: 'R4B')]
class AllowedUnitsExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|CanonicalPrimitive|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive|null) */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-allowedUnits',
            value: $this->value,
        );
    }
}
