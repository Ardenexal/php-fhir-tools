<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeSystemContentModeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies a code system that is supported by the server. If there is a no code system URL, then this declares the general assumptions a client can make about support for any CodeSystem resource.
 */
#[FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.codeSystem', fhirVersion: 'R5')]
class FHIRTerminologyCapabilitiesCodeSystem extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCanonical|null uri Canonical identifier for the code system, represented as a URI */
        public ?FHIRCanonical $uri = null,
        /** @var array<FHIRTerminologyCapabilitiesCodeSystemVersion> version Version of Code System supported */
        public array $version = [],
        /** @var FHIRCodeSystemContentModeType|null content not-present | example | fragment | complete | supplement */
        #[NotBlank]
        public ?FHIRCodeSystemContentModeType $content = null,
        /** @var FHIRBoolean|null subsumption Whether subsumption is supported */
        public ?FHIRBoolean $subsumption = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
