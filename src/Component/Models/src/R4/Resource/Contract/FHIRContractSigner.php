<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRSignature;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Parties with legal standing in the Contract, including the principal parties, the grantor(s) and grantee(s), which are any person or organization bound by the contract, and any ancillary parties, which facilitate the execution of the contract such as a notary or witness.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.signer', fhirVersion: 'R4')]
class FHIRContractSigner extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCoding|null type Contract Signatory Role */
        #[NotBlank]
        public ?FHIRCoding $type = null,
        /** @var FHIRReference|null party Contract Signatory Party */
        #[NotBlank]
        public ?FHIRReference $party = null,
        /** @var array<FHIRSignature> signature Contract Documentation Signature */
        public array $signature = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
