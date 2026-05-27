<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - MnM WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/timing-exact
 *
 * @description If true, indicates that the specified times, frequencies, periods are expected to be adhered to as precisely as possible.  If false, indicates that a typical degree of variability based on institutional and/or patient convenience is acceptable.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/timing-exact', fhirVersion: 'R4B')]
class ExactExtension extends Extension
{
    public function __construct(
        /** @var bool|null valueBoolean Value of extension */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $valueBoolean = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/timing-exact',
            value: $this->valueBoolean,
        );
    }
}
