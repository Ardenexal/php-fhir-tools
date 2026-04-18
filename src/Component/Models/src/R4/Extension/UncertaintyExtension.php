<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/iso21090-uncertainty
 *
 * @description The primary measure of variance/uncertainty of the value (the square root of the sum of the squares of the differences between all data points and the mean).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/iso21090-uncertainty', fhirVersion: 'R4')]
class UncertaintyExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/iso21090-uncertainty',
            value: $this->valueDecimal,
        );
    }
}
