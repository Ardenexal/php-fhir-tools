<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Filter Properties supported.
 */
#[FHIRBackboneElement(
    parentResource: 'TerminologyCapabilities',
    elementPath: 'TerminologyCapabilities.codeSystem.version.filter',
    fhirVersion: 'R5',
)]
class FHIRTerminologyCapabilitiesCodeSystemVersionFilter extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Code of the property supported */
        #[NotBlank]
        public ?\FHIRCode $code = null,
        /** @var array<FHIRCode> op Operations supported for the property */
        public array $op = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
