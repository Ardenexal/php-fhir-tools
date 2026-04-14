<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - CDS WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-initiatingPerson
 *
 * @description The person initiating the request.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-initiatingPerson', fhirVersion: 'R4B')]
class InitiatingPersonExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-initiatingPerson',
            value: $this->valueReference,
        );
    }
}
