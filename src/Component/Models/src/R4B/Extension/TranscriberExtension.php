<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - CBCC WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/consent-Transcriber
 *
 * @description Any person/thing who transcribed the consent into the system.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/consent-Transcriber', fhirVersion: 'R4B')]
class TranscriberExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/consent-Transcriber',
            value: $this->valueReference,
        );
    }
}
