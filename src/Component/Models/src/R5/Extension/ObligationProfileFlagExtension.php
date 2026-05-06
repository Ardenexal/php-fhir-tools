<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/obligation-profile-flag
 *
 * @description Profiles marked to be 'Obligation Profiles' cannot introduce new structural elements or slicing, but they can change the cardinality, add additional bindings, and additional obligations on the elements that are already defined
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/obligation-profile-flag', fhirVersion: 'R5')]
class ObligationProfileFlagExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/obligation-profile-flag',
            value: $this->valueBoolean,
        );
    }
}
