<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Account;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\PositiveIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The party(s) that are responsible for covering the payment of this account, and what order should they be applied to the account.
 */
#[FHIRBackboneElement(parentResource: 'Account', elementPath: 'Account.coverage', fhirVersion: 'R4')]
class AccountCoverage extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null coverage The party(s), such as insurances, that may contribute to the payment of this account */
        #[NotBlank]
        public ?Reference $coverage = null,
        /** @var PositiveIntPrimitive|null priority The priority of the coverage in the context of this account */
        public ?PositiveIntPrimitive $priority = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
