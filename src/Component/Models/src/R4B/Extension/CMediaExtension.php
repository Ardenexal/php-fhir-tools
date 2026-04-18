<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Attachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Patient Care
 *
 * @see http://hl7.org/fhir/StructureDefinition/communication-media
 *
 * @description It contains enriched media representation of the alert message, such as a voice recording.  This may be used, for example for compliance with jurisdictional accessibility requirements, literacy issues, or translations of the unstructured text content in other languages.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/communication-media', fhirVersion: 'R4B')]
class CMediaExtension extends Extension
{
    public function __construct(
        /** @var Attachment|null valueAttachment Value of extension */
        #[FhirProperty(fhirType: 'Attachment', propertyKind: 'complex')]
        public ?Attachment $valueAttachment = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/communication-media',
            value: $this->valueAttachment,
        );
    }
}
