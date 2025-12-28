<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description List of Computable Policy Rule Language Representations of this Contract.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.rule', fhirVersion: 'R4B')]
class FHIRContractRule extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRAttachment|FHIRReference|null contentX Computable Contract Rules */
        #[NotBlank]
        public \FHIRAttachment|\FHIRReference|null $contentX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
