<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;

/**
 * @author HL7 International / Clinical Genomics
 *
 * @see http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-observation
 *
 * @description Allows capturing risk-relevant observations about the relative that aren't themselves a specific health condition; e.g. Certain ethnic ancestries that are disease-relevant, presence of particular genetic markers, etc.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-observation', fhirVersion: 'R4B')]
class FMHObservationExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/family-member-history-genetics-observation',
            value: $this->valueReference,
        );
    }
}
