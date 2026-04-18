<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/humanname-partner-prefix
 *
 * @description The prefix portion (e.g. voorvoegsel) of the family name that is derived from the person's partner's surname, as distinguished from any portion that is derived from the surname of the person's own.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/humanname-partner-prefix', fhirVersion: 'R5')]
class PartnerPrefixExtension extends Extension
{
    public function __construct(
        /** @var StringPrimitive|null valueString Value of extension */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $valueString = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/humanname-partner-prefix',
            value: $this->valueString,
        );
    }
}
