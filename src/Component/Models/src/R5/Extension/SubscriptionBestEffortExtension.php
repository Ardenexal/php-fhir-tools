<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/subscription-best-effort
 *
 * @description If true, indicates the channel should be treated as though it is using a non-guaranteed delivery mechanism, effectively labeling the subscription as 'best effort'.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/subscription-best-effort', fhirVersion: 'R5')]
class SubscriptionBestEffortExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/subscription-best-effort',
            value: $this->valueBoolean,
        );
    }
}
