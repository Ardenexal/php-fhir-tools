<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A single data collection that is shared as part of the scenario.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.instance', fhirVersion: 'R5')]
class FHIRExampleScenarioInstance extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null key ID or acronym of the instance */
        #[NotBlank]
        public FHIRString|string|null $key = null,
        /** @var FHIRCoding|null structureType Data structure for example */
        #[NotBlank]
        public ?FHIRCoding $structureType = null,
        /** @var FHIRString|string|null structureVersion E.g. 4.0.1 */
        public FHIRString|string|null $structureVersion = null,
        /** @var FHIRCanonical|FHIRUri|null structureProfileX Rules instance adheres to */
        public FHIRCanonical|FHIRUri|null $structureProfileX = null,
        /** @var FHIRString|string|null title Label for instance */
        #[NotBlank]
        public FHIRString|string|null $title = null,
        /** @var FHIRMarkdown|null description Human-friendly description of the instance */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRReference|null content Example instance data */
        public ?FHIRReference $content = null,
        /** @var array<FHIRExampleScenarioInstanceVersion> version Snapshot of instance that changes */
        public array $version = [],
        /** @var array<FHIRExampleScenarioInstanceContainedInstance> containedInstance Resources contained in the instance */
        public array $containedInstance = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
