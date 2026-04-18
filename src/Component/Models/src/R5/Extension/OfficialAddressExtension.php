<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/address-official
 *
 * @description Indicate that this address is meant to be the 'official' address for that person. What an 'official' address is depends on the country. This extension allows to specify if this address is or it is not the official address, or to indicate that this is the official address for that country.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/address-official', fhirVersion: 'R5')]
class OfficialAddressExtension extends Extension
{
    public function __construct(
        /** @var bool|CodeableConcept|null value Value of extension */
        #[FhirProperty(fhirType: 'choice', propertyKind: 'choice', isChoice: true)]
        bool|CodeableConcept|null $value = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/address-official',
            value: $value,
        );
    }
}
