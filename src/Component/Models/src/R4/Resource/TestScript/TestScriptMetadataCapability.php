<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Capabilities that must exist and are assumed to function correctly on the FHIR server being tested.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.metadata.capability', fhirVersion: 'R4')]
class TestScriptMetadataCapability extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var bool|null required Are the capabilities required? */
        #[NotBlank]
        public ?bool $required = null,
        /** @var bool|null validated Are the capabilities validated? */
        #[NotBlank]
        public ?bool $validated = null,
        /** @var StringPrimitive|string|null description The expected capabilities of the server */
        public StringPrimitive|string|null $description = null,
        /** @var array<int> origin Which origin server these requirements apply to */
        public array $origin = [],
        /** @var int|null destination Which server these requirements apply to */
        public ?int $destination = null,
        /** @var array<UriPrimitive> link Links to the FHIR specification */
        public array $link = [],
        /** @var CanonicalPrimitive|null capabilities Required Capability Statement */
        #[NotBlank]
        public ?CanonicalPrimitive $capabilities = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
