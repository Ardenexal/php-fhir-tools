<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Summary of the article or artifact.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.abstract', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactAbstract extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type The kind of abstract */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null language Used to express the specific language */
        public ?FHIRCodeableConcept $language = null,
        /** @var FHIRMarkdown|null text Abstract content */
        #[NotBlank]
        public ?FHIRMarkdown $text = null,
        /** @var FHIRMarkdown|null copyright Copyright notice for the abstract */
        public ?FHIRMarkdown $copyright = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
