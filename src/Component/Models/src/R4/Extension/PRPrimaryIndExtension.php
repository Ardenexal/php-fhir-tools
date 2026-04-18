<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/practitionerrole-primaryInd
 *
 * @description Flag indicating if the specialty is the primary specialty of the provider. Normally, a practitioner will have one primary specialty, but in some cases more than one can be primary.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/practitionerrole-primaryInd', fhirVersion: 'R4')]
class PRPrimaryIndExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/practitionerrole-primaryInd',
            value: $this->valueBoolean,
        );
    }
}
