<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRelatedArtifact;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;

/**
 * @description A component comment, classifier, or rating of the artifact.
 */
#[FHIRBackboneElement(parentResource: 'ArtifactAssessment', elementPath: 'ArtifactAssessment.content', fhirVersion: 'R5')]
class FHIRArtifactAssessmentContent extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRArtifactAssessmentInformationTypeType|null informationType comment | classifier | rating | container | response | change-request */
        public ?FHIRArtifactAssessmentInformationTypeType $informationType = null,
        /** @var FHIRMarkdown|null summary Brief summary of the content */
        public ?FHIRMarkdown $summary = null,
        /** @var FHIRCodeableConcept|null type What type of content */
        public ?FHIRCodeableConcept $type = null,
        /** @var array<FHIRCodeableConcept> classifier Rating, classifier, or assessment */
        public array $classifier = [],
        /** @var FHIRQuantity|null quantity Quantitative rating */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRReference|null author Who authored the content */
        public ?FHIRReference $author = null,
        /** @var array<FHIRUri> path What the comment is directed to */
        public array $path = [],
        /** @var array<FHIRRelatedArtifact> relatedArtifact Additional information */
        public array $relatedArtifact = [],
        /** @var FHIRBoolean|null freeToShare Acceptable to publicly share the resource content */
        public ?FHIRBoolean $freeToShare = null,
        /** @var array<FHIRArtifactAssessmentContent> component Contained content */
        public array $component = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
