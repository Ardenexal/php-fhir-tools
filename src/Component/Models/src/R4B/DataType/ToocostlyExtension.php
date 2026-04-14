<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/valueset-toocostly
 *
 * @description Marks that the expansion is  incomplete, because the full value set is too large to represent, and the client asked for an incomplete fragment.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/valueset-toocostly', fhirVersion: 'R4B')]
class ToocostlyExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/valueset-toocostly',
            value: $this->valueBoolean,
        );
    }
}
