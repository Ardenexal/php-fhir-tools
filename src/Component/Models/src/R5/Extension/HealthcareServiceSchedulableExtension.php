<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Patient Administration
 *
 * @see http://hl7.org/fhir/StructureDefinition/healthcareservice-schedulable
 *
 * @description Indicates whether the service is directly schedulable or not. For example, a generic "Radiology" service is not schedulable, but a CT Scan is schedulable.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/healthcareservice-schedulable', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'HealthcareService')]
class HealthcareServiceSchedulableExtension extends Extension
{
    public function __construct(
        /** @var bool|null valueBoolean Whether the service is schedulable */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $valueBoolean = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/healthcareservice-schedulable',
            value: $this->valueBoolean,
        );
    }
}
