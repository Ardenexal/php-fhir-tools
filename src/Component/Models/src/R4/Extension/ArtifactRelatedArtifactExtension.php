<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact;

/**
 * @author HL7 International / Clinical Decision Support
 *
 * @see http://hl7.org/fhir/StructureDefinition/artifact-relatedArtifact
 *
 * @description Related artifacts such as additional documentation, justification, dependencies, bibliographic references, and predecessor and successor artifacts.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/artifact-relatedArtifact', fhirVersion: 'R4')]
class ArtifactRelatedArtifactExtension extends Extension
{
    public function __construct(
        /** @var RelatedArtifact|null valueRelatedArtifact Value of extension */
        #[FhirProperty(fhirType: 'RelatedArtifact', propertyKind: 'complex')]
        public ?RelatedArtifact $valueRelatedArtifact = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/artifact-relatedArtifact',
            value: $this->valueRelatedArtifact,
        );
    }
}
