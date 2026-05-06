<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/11179-permitted-value-valueset
 *
 * @description Allows expressing the value set that must be stored internally by the system (as distinct from the base value set which defines values for exchange).
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/11179-permitted-value-valueset', fhirVersion: 'R4')]
class PermittedValueValuesetExtension extends Extension
{
    public function __construct(
        /** @var CanonicalPrimitive|null valueCanonical Value of extension */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $valueCanonical = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/11179-permitted-value-valueset',
            value: $this->valueCanonical,
        );
    }
}
