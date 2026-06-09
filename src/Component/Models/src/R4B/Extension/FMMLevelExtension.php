<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/structuredefinition-fmm
 *
 * @description The FMM level assigned to the artifact. For further information about FMM levels, see [FHIR Maturity Model](https://confluence.hl7.org/spaces/FHIR/pages/35718679/FHIR+Maturity+Model).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-fmm', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'DomainResource')]
#[FHIRExtensionContext(type: 'element', expression: 'Element')]
class FMMLevelExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
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
            url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-fmm',
            value: $this->valueInteger,
        );
    }
}
