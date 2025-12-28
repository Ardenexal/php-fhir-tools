<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The defined version of the cited artifact.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.version', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactVersion extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null value The version number or other version identifier */
        #[NotBlank]
        public \FHIRString|string|null $value = null,
        /** @var FHIRReference|null baseCitation Citation for the main version of the cited artifact */
        public ?\FHIRReference $baseCitation = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
