<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Claim;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Financial instruments for reimbursement for the health care products and services specified on the claim.
 */
#[FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.insurance', fhirVersion: 'R4')]
class ClaimInsurance extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var PositiveIntPrimitive|null sequence Insurance instance identifier */
        #[NotBlank]
        public ?PositiveIntPrimitive $sequence = null,
        /** @var bool|null focal Coverage to be used for adjudication */
        #[NotBlank]
        public ?bool $focal = null,
        /** @var Identifier|null identifier Pre-assigned Claim number */
        public ?Identifier $identifier = null,
        /** @var Reference|null coverage Insurance information */
        #[NotBlank]
        public ?Reference $coverage = null,
        /** @var StringPrimitive|string|null businessArrangement Additional provider contract number */
        public StringPrimitive|string|null $businessArrangement = null,
        /** @var array<StringPrimitive|string> preAuthRef Prior authorization reference number */
        public array $preAuthRef = [],
        /** @var Reference|null claimResponse Adjudication results */
        public ?Reference $claimResponse = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
