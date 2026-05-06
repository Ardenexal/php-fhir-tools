<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\RelatedArtifact;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/workflow-relatedArtifact
 *
 * @description Documents the 'knowledge artifacts' relevant to the base resource such as citations, supporting evidence, documentation of processes, caveats around testing methodology.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-relatedArtifact', fhirVersion: 'R4B')]
class RelatedArtifactExtension extends Extension
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
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-relatedArtifact',
            value: $this->valueRelatedArtifact,
        );
    }
}
