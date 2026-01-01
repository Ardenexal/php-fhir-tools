<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A link to the FHIR specification that this test is covering.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.metadata.link', fhirVersion: 'R4')]
class FHIRTestScriptMetadataLink extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url URL to the specification */
        #[NotBlank]
        public ?FHIRUri $url = null,
        /** @var FHIRString|string|null description Short description */
        public FHIRString|string|null $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
