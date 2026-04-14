<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - [WG Name] WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/structuredefinition-dependencies
 *
 * @description Instances can only be valid against this StructureDefinition, if they also sucessfully validate against the dependent profile identified in this extension.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-dependencies', fhirVersion: 'R4B')]
class DependenciesExtension extends Extension
{
    public function __construct(
        /** @var CanonicalPrimitive|null valueCanonical Value of extension */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive $valueCanonical = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/structuredefinition-dependencies',
            value: $this->valueCanonical,
        );
    }
}
