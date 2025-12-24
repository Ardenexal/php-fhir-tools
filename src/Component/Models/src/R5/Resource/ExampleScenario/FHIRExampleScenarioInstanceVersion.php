<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Represents the instance as it was at a specific time-point.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.instance.version', fhirVersion: 'R5')]
class FHIRExampleScenarioInstanceVersion extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null key ID or acronym of the version */
        #[NotBlank]
        public FHIRString|string|null $key = null,
        /** @var FHIRString|string|null title Label for instance version */
        #[NotBlank]
        public FHIRString|string|null $title = null,
        /** @var FHIRMarkdown|null description Details about version */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRReference|null content Example instance version data */
        public ?FHIRReference $content = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
