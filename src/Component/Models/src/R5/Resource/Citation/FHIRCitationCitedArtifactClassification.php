<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The assignment to an organizing scheme.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.classification', fhirVersion: 'R5')]
class FHIRCitationCitedArtifactClassification extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type The kind of classifier (e.g. publication type, keyword) */
        public ?\FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> classifier The specific classification value */
        public array $classifier = [],
        /** @var array<FHIRReference> artifactAssessment Complex or externally created classification */
        public array $artifactAssessment = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
