<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/usagecontext-group
 *
 * @description Defines the group in which this usage context is a member. Multiple groups are "OR'ed", contexts within a group are "AND'ed".
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/usagecontext-group', fhirVersion: 'R4B')]
class GroupExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/usagecontext-group',
            value: $this->valueString,
        );
    }
}
