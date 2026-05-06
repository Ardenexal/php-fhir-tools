<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ExtendedContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-extended-contact-detail
 *
 * @description Contact details (including purpose and address) to assist a user in finding and communicating with the publisher.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-extended-contact-detail', fhirVersion: 'R5')]
class ArtifactExtendedContactDetailExtension extends Extension
{
    public function __construct(
        /** @var ExtendedContactDetail|null valueExtendedContactDetail Value of extension */
        #[FhirProperty(fhirType: 'ExtendedContactDetail', propertyKind: 'complex')]
        public ?ExtendedContactDetail $valueExtendedContactDetail = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-extended-contact-detail',
            value: $this->valueExtendedContactDetail,
        );
    }
}
