<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUrl;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A page / section in the implementation guide. The root page is the implementation guide home page.
 */
#[FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.definition.page', fhirVersion: 'R4B')]
class FHIRImplementationGuideDefinitionPage extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUrl|FHIRReference|null nameX Where to find that page */
        #[NotBlank]
        public FHIRUrl|FHIRReference|null $nameX = null,
        /** @var FHIRString|string|null title Short title shown for navigational assistance */
        #[NotBlank]
        public FHIRString|string|null $title = null,
        /** @var FHIRGuidePageGenerationType|null generation html | markdown | xml | generated */
        #[NotBlank]
        public ?FHIRGuidePageGenerationType $generation = null,
        /** @var array<FHIRImplementationGuideDefinitionPage> page Nested Pages / Sections */
        public array $page = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
