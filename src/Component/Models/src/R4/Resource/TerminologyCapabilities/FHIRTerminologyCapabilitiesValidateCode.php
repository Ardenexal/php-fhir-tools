<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Information about the [ValueSet/$validate-code](valueset-operation-validate-code.html) operation.
 */
#[FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.validateCode', fhirVersion: 'R4')]
class FHIRTerminologyCapabilitiesValidateCode extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|null translations Whether translations are validated */
        #[NotBlank]
        public ?FHIRBoolean $translations = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
