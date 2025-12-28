<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Security labels that protect the handling of information about the term and its elements, which may be specifically identified..
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.securityLabel', fhirVersion: 'R4B')]
class FHIRContractTermSecurityLabel extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRUnsignedInt> number Link to Security Labels */
        public array $number = [],
        /** @var FHIRCoding|null classification Confidentiality Protection */
        #[NotBlank]
        public ?\FHIRCoding $classification = null,
        /** @var array<FHIRCoding> category Applicable Policy */
        public array $category = [],
        /** @var array<FHIRCoding> control Handling Instructions */
        public array $control = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
