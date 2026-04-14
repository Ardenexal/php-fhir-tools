<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @author Health Level Seven, Inc. - FHIR WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/workflow-relatedArtifact
 *
 * @description Documentation  or 'knowledge artifacts' relevant to the base resource such as citations, supporting evidence, documentation of processes, caveats around testing methodology.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-relatedArtifact', fhirVersion: 'R4')]
class RelatedArtifactExtension extends Extension
{
    public function __construct(
        /** @var RelatedArtifact|null valueRelatedArtifact Value of extension */
        #[FhirProperty(fhirType: 'RelatedArtifact', propertyKind: 'complex')]
        public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\RelatedArtifact $valueRelatedArtifact = null,
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
