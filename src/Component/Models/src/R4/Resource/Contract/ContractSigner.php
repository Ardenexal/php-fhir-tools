<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Parties with legal standing in the Contract, including the principal parties, the grantor(s) and grantee(s), which are any person or organization bound by the contract, and any ancillary parties, which facilitate the execution of the contract such as a notary or witness.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.signer', fhirVersion: 'R4')]
class ContractSigner extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Coding|null type Contract Signatory Role */
        #[NotBlank]
        public ?Coding $type = null,
        /** @var Reference|null party Contract Signatory Party */
        #[NotBlank]
        public ?Reference $party = null,
        /** @var array<Signature> signature Contract Documentation Signature */
        public array $signature = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
