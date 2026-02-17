<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\GuidePageGenerationType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A page / section in the implementation guide. The root page is the implementation guide home page.
 */
#[FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.definition.page', fhirVersion: 'R4')]
class ImplementationGuideDefinitionPage extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var UrlPrimitive|Reference|null nameX Where to find that page */
        #[NotBlank]
        public UrlPrimitive|Reference|null $nameX = null,
        /** @var StringPrimitive|string|null title Short title shown for navigational assistance */
        #[NotBlank]
        public StringPrimitive|string|null $title = null,
        /** @var GuidePageGenerationType|null generation html | markdown | xml | generated */
        #[NotBlank]
        public ?GuidePageGenerationType $generation = null,
        /** @var array<ImplementationGuideDefinitionPage> page Nested Pages / Sections */
        public array $page = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
