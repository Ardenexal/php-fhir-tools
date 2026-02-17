<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Security labels that protect the handling of information about the term and its elements, which may be specifically identified..
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.securityLabel', fhirVersion: 'R4')]
class ContractTermSecurityLabel extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<UnsignedIntPrimitive> number Link to Security Labels */
        public array $number = [],
        /** @var Coding|null classification Confidentiality Protection */
        #[NotBlank]
        public ?Coding $classification = null,
        /** @var array<Coding> category Applicable Policy */
        public array $category = [],
        /** @var array<Coding> control Handling Instructions */
        public array $control = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
