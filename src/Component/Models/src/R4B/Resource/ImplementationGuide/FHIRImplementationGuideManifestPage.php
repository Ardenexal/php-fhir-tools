<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about a page within the IG.
 */
#[FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.manifest.page', fhirVersion: 'R4B')]
class FHIRImplementationGuideManifestPage extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name HTML page name */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Title of the page, for references */
        public FHIRString|string|null $title = null,
        /** @var array<FHIRString|string> anchor Anchor available on the page */
        public array $anchor = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
