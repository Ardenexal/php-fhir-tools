<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-editor
 *
 * @description An individual or organization primarily responsible for internal coherence of the artifact.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-editor', fhirVersion: 'R5')]
class ArtifactEditorExtension extends Extension
{
    public function __construct(
        /** @var ContactDetail|null valueContactDetail Value of extension */
        #[FhirProperty(fhirType: 'ContactDetail', propertyKind: 'complex')]
        public ?ContactDetail $valueContactDetail = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-editor',
            value: $this->valueContactDetail,
        );
    }
}
