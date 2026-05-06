<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/questionnaireresponse-signature
 *
 * @description Represents a wet or electronic signature for either the form overall or for the question or item it's associated with.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaireresponse-signature', fhirVersion: 'R4')]
class QRSignatureExtension extends Extension
{
    public function __construct(
        /** @var Signature|null valueSignature Value of extension */
        #[FhirProperty(fhirType: 'Signature', propertyKind: 'complex')]
        public ?Signature $valueSignature = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/questionnaireresponse-signature',
            value: $this->valueSignature,
        );
    }
}
