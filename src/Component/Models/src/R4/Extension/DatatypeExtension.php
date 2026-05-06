<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/_datatype
 *
 * @description Used when the actual type is not allowed by the definition of the element, or not specified in the context (e.g. in a cross-version extension). In general, this should only arise when wrangling between versions using cross-version extensions - see [Cross Version Extensions](versions.html#extensions). For legacy reasons, this extension has a type of `string` but it behaves as a URI with a default namespace (per ElementDefinition.type.code). Also note that the default namespace behavior is version independent
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/_datatype', fhirVersion: 'R4')]
class DatatypeExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/_datatype',
            value: $this->valueString,
        );
    }
}
