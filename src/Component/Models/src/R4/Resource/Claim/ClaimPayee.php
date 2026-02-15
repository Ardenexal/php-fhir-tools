<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The party to be reimbursed for cost of the products and services according to the terms of the policy.
 */
#[FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.payee', fhirVersion: 'R4')]
class ClaimPayee extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Category of recipient */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var Reference|null party Recipient reference */
        public ?Reference $party = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
