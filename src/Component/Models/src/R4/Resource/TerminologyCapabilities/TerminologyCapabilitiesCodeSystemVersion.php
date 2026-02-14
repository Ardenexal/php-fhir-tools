<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description For the code system, a list of versions that are supported by the server.
 */
#[FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.codeSystem.version', fhirVersion: 'R4')]
class TerminologyCapabilitiesCodeSystemVersion extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null code Version identifier for this version */
        public StringPrimitive|string|null $code = null,
        /** @var bool|null isDefault If this is the default version for this code system */
        public ?bool $isDefault = null,
        /** @var bool|null compositional If compositional grammar is supported */
        public ?bool $compositional = null,
        /** @var array<CodePrimitive> language Language Displays supported */
        public array $language = [],
        /** @var array<TerminologyCapabilitiesCodeSystemVersionFilter> filter Filter Properties supported */
        public array $filter = [],
        /** @var array<CodePrimitive> property Properties supported for $lookup */
        public array $property = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
