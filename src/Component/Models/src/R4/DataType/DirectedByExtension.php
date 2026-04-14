<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/procedure-directedBy
 *
 * @description The target of the extension is a distinct actor from the requester and has decision-making authority over the service and takes direct responsibility to manage the service.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/procedure-directedBy', fhirVersion: 'R4')]
class DirectedByExtension extends Extension
{
    public function __construct(
        /** @var CodeableConcept|Reference|null value Value of extension (\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference|null) */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/procedure-directedBy',
            value: $this->value,
        );
    }
}
