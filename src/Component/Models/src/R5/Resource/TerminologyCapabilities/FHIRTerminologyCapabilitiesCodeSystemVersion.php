<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description For the code system, a list of versions that are supported by the server.
 */
#[FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.codeSystem.version', fhirVersion: 'R5')]
class FHIRTerminologyCapabilitiesCodeSystemVersion extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null code Version identifier for this version */
        public \FHIRString|string|null $code = null,
        /** @var FHIRBoolean|null isDefault If this is the default version for this code system */
        public ?\FHIRBoolean $isDefault = null,
        /** @var FHIRBoolean|null compositional If compositional grammar is supported */
        public ?\FHIRBoolean $compositional = null,
        /** @var array<FHIRCommonLanguagesType> language Language Displays supported */
        public array $language = [],
        /** @var array<FHIRTerminologyCapabilitiesCodeSystemVersionFilter> filter Filter Properties supported */
        public array $filter = [],
        /** @var array<FHIRCode> property Properties supported for $lookup */
        public array $property = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
