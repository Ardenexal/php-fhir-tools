<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The component of the article or artifact.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.part', fhirVersion: 'R5')]
class FHIRCitationCitedArtifactPart extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type The kind of component */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRString|string|null value The specification of the component */
        public \FHIRString|string|null $value = null,
        /** @var FHIRReference|null baseCitation The citation for the full article or artifact */
        public ?\FHIRReference $baseCitation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
