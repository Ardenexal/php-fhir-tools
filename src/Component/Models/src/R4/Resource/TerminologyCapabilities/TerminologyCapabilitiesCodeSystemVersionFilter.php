<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TerminologyCapabilities;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Filter Properties supported.
 */
#[FHIRBackboneElement(
    parentResource: 'TerminologyCapabilities',
    elementPath: 'TerminologyCapabilities.codeSystem.version.filter',
    fhirVersion: 'R4',
)]
class TerminologyCapabilitiesCodeSystemVersionFilter extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodePrimitive|null code Code of the property supported */
        #[NotBlank]
        public ?CodePrimitive $code = null,
        /** @var array<CodePrimitive> op Operations supported for the property */
        public array $op = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
