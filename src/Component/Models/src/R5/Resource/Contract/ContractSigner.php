<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\Contract;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Signature;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Parties with legal standing in the Contract, including the principal parties, the grantor(s) and grantee(s), which are any person or organization bound by the contract, and any ancillary parties, which facilitate the execution of the contract such as a notary or witness.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.signer', fhirVersion: 'R5')]
class ContractSigner extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var Coding|null type Contract Signatory Role */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Coding $type = null,
        /** @var Reference|null party Contract Signatory Party */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Reference $party = null,
        /** @var array<Signature> signature Contract Documentation Signature */
        #[FhirProperty(
            fhirType: 'Signature',
            propertyKind: 'complex',
            isArray: true,
            isRequired: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Signature',
        )]
        public array $signature = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
