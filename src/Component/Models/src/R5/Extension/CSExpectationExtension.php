<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/capabilitystatement-expectation
 *
 * @description Defines the level of expectation associated with a given system capability.  See the capabilitystatement-prohibited modifier extension to set expectations to *not* support a feature.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-expectation', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.acceptLanguage')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.document')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.format')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.implementationGuide')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.imports')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.patchFormat')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.interaction')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.operation')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.extension')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.conditionalCreate')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.conditionalDelete')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.conditionalRead')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.conditionalUpdate')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.conditionalPatch')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.interaction')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.operation')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.readHistory')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.referencePolicy')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.searchInclude')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.searchParam')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.searchRevInclude')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.supportedProfile')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.updateCreate')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.resource.versioning')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.searchParam')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.security')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.security.cors')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.rest.security.service')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.messaging.reliableCache')]
#[FHIRExtensionContext(type: 'element', expression: 'CapabilityStatement.messaging.supportedMessage')]
#[FHIRExtensionContext(type: 'element', expression: 'SearchParameter.multipleOr')]
#[FHIRExtensionContext(type: 'element', expression: 'SearchParameter.multipleAnd')]
#[FHIRExtensionContext(type: 'element', expression: 'SearchParameter.comparator')]
#[FHIRExtensionContext(type: 'element', expression: 'SearchParameter.modifier')]
#[FHIRExtensionContext(type: 'element', expression: 'SearchParameter.chain')]
#[FHIRExtensionContext(
    type: 'extension',
    expression: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-search-parameter-combination',
)]
class CSExpectationExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var CodePrimitive|null valueCode Value of extension */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CodePrimitive $valueCode = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/capabilitystatement-expectation',
            value: $this->valueCode,
        );
    }
}
