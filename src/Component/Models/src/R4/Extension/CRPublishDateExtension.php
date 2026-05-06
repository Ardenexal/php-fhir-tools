<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/metadataresource-publish-date
 *
 * @description The date this artifact was first published.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/metadataresource-publish-date', fhirVersion: 'R4')]
class CRPublishDateExtension extends Extension
{
    public function __construct(
        /** @var DatePrimitive|null valueDate Value of extension */
        #[FhirProperty(fhirType: 'date', propertyKind: 'primitive')]
        public ?DatePrimitive $valueDate = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/metadataresource-publish-date',
            value: $this->valueDate,
        );
    }
}
