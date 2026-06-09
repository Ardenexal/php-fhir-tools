<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-isOwned
 *
 * @description Whether or not the referenced artifact is owned by the referencing artifact.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-isOwned', fhirVersion: 'R4B')]
#[FHIRExtensionContext(type: 'element', expression: 'RelatedArtifact')]
#[FHIRExtensionContext(type: 'element', expression: 'ActivityDefinition.library')]
#[FHIRExtensionContext(type: 'element', expression: 'PlanDefinition.library')]
#[FHIRExtensionContext(type: 'element', expression: 'Measure.library')]
#[FHIRExtensionContext(type: 'element', expression: 'DomainResource.extension')]
#[FHIRExtensionContext(type: 'fhirpath', expression: 'type.exists() and type = \'composed-of\'')]
class ArtifactIsOwnedExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
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
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-isOwned',
            value: $this->valueBoolean,
        );
    }
}
