<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Provenance and copyright of classification.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.classification.whoClassified', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactClassificationWhoClassified extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null person Person who created the classification */
        public ?FHIRReference $person = null,
        /** @var FHIRReference|null organization Organization who created the classification */
        public ?FHIRReference $organization = null,
        /** @var FHIRReference|null publisher The publisher of the classification, not the publisher of the article or artifact being cited */
        public ?FHIRReference $publisher = null,
        /** @var FHIRString|string|null classifierCopyright Rights management statement for the classification */
        public FHIRString|string|null $classifierCopyright = null,
        /** @var FHIRBoolean|null freeToShare Acceptable to re-use the classification */
        public ?FHIRBoolean $freeToShare = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
