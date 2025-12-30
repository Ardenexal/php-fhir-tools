<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Used to record a display of the author/contributor list without separate coding for each list member.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.contributorship.summary', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactContributorshipSummary extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Either authorList or contributorshipStatement */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null style The format for the display string */
        public ?FHIRCodeableConcept $style = null,
        /** @var FHIRCodeableConcept|null source Used to code the producer or rule for creating the display string */
        public ?FHIRCodeableConcept $source = null,
        /** @var FHIRMarkdown|null value The display string for the author list, contributor list, or contributorship statement */
        #[NotBlank]
        public ?FHIRMarkdown $value = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
