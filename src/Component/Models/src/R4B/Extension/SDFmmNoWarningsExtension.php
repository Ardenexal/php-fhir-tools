<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/structuredefinition-fmm-no-warnings
 *
 * @description The FMM level that would be assigned to the artifact if it had no warnings.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-fmm-no-warnings', fhirVersion: 'R4B')]
class SDFmmNoWarningsExtension extends Extension
{
    public function __construct(
        /** @var int|null valueInteger Value of extension */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $valueInteger = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-fmm-no-warnings',
            value: $this->valueInteger,
        );
    }
}
