<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Capabilities that must exist and are assumed to function correctly on the FHIR server being tested.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.metadata.capability', fhirVersion: 'R4')]
class FHIRTestScriptMetadataCapability extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|null required Are the capabilities required? */
        #[NotBlank]
        public ?FHIRBoolean $required = null,
        /** @var FHIRBoolean|null validated Are the capabilities validated? */
        #[NotBlank]
        public ?FHIRBoolean $validated = null,
        /** @var FHIRString|string|null description The expected capabilities of the server */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRInteger> origin Which origin server these requirements apply to */
        public array $origin = [],
        /** @var FHIRInteger|null destination Which server these requirements apply to */
        public ?FHIRInteger $destination = null,
        /** @var array<FHIRUri> link Links to the FHIR specification */
        public array $link = [],
        /** @var FHIRCanonical|null capabilities Required Capability Statement */
        #[NotBlank]
        public ?FHIRCanonical $capabilities = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
