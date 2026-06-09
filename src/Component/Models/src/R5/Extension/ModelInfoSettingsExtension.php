<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-modelInfoSettings
 *
 * @description Specifies the settings to be used for constructing modelinfo from profile definitions. This extension is used on ImplementationGuide or asset-collection Library resources to provide a way for authors to configure additional information about the ModelInfo for profiles defined in the ImplementationGuide or Asset Collection. The extension is used in profiles in the Using CQL With FHIR IG. See the ModelInfo discussion there for additional information on how to use this extension.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/cqf-modelInfoSettings', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'Library')]
#[FHIRExtensionContext(type: 'element', expression: 'ImplementationGuide')]
#[FHIRExtensionContext(type: 'element', expression: 'RelatedArtifact')]
class ModelInfoSettingsExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/cqf-modelInfoSettings',
            value: $this->valueReference,
        );
    }
}
