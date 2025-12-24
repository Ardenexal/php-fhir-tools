<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Financial instruments for reimbursement for the health care products and services specified on the claim.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Claim', elementPath: 'Claim.insurance', fhirVersion: 'R5')]
class FHIRClaimInsurance extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPositiveInt|null sequence Insurance instance identifier */
        #[NotBlank]
        public ?FHIRPositiveInt $sequence = null,
        /** @var FHIRBoolean|null focal Coverage to be used for adjudication */
        #[NotBlank]
        public ?FHIRBoolean $focal = null,
        /** @var FHIRIdentifier|null identifier Pre-assigned Claim number */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRReference|null coverage Insurance information */
        #[NotBlank]
        public ?FHIRReference $coverage = null,
        /** @var FHIRString|string|null businessArrangement Additional provider contract number */
        public FHIRString|string|null $businessArrangement = null,
        /** @var array<FHIRString|string> preAuthRef Prior authorization reference number */
        public array $preAuthRef = [],
        /** @var FHIRReference|null claimResponse Adjudication results */
        public ?FHIRReference $claimResponse = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
