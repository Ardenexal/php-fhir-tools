<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/maxSize
 *
 * @description For attachment answers, indicates the maximum size in bytes an attachment can be.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/maxSize', fhirVersion: 'R4B')]
class MaxSizeExtension extends Extension
{
    public function __construct(
        /** @var string|null valueDecimal Value of extension */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $valueDecimal = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/maxSize',
            value: $this->valueDecimal,
        );
    }
}
