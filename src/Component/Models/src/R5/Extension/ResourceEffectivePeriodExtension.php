<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/resource-effectivePeriod
 *
 * @description The period during which the resource content was or is planned to be effective.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/resource-effectivePeriod', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'StructureDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'StructureMap')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement')]
#[FHIRExtensionContext(type: 'element', expression: 'OperationDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'SearchParameter')]
#[FHIRExtensionContext(type: 'element', expression: 'CompartmentDefinition')]
#[FHIRExtensionContext(type: 'element', expression: 'ImplementationGuide', startFhirVersion: '4.0')]
#[FHIRExtensionContext(type: 'element', expression: 'CodeSystem', endFhirVersion: '4.3')]
#[FHIRExtensionContext(type: 'element', expression: 'ValueSet', endFhirVersion: '4.3')]
#[FHIRExtensionContext(type: 'element', expression: 'ConceptMap', endFhirVersion: '4.3')]
#[FHIRExtensionContext(type: 'element', expression: 'NamingSystem', endFhirVersion: '4.3')]
#[FHIRExtensionContext(type: 'element', expression: 'Group')]
class ResourceEffectivePeriodExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var Period|null valuePeriod Value of extension */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $valuePeriod = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/resource-effectivePeriod',
            value: $this->valuePeriod,
        );
    }
}
