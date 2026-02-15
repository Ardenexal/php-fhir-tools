<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Fixture in the test script - by reference (uri). All fixtures are required for the test script to execute.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.fixture', fhirVersion: 'R4')]
class TestScriptFixture extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var bool|null autocreate Whether or not to implicitly create the fixture during setup */
        #[NotBlank]
        public ?bool $autocreate = null,
        /** @var bool|null autodelete Whether or not to implicitly delete the fixture during teardown */
        #[NotBlank]
        public ?bool $autodelete = null,
        /** @var Reference|null resource Reference of the resource */
        public ?Reference $resource = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
