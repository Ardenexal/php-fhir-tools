<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRPositiveInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The party(s) that are responsible for covering the payment of this account, and what order should they be applied to the account.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Account', elementPath: 'Account.coverage', fhirVersion: 'R4')]
class FHIRAccountCoverage extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null coverage The party(s), such as insurances, that may contribute to the payment of this account */
        #[NotBlank]
        public ?FHIRReference $coverage = null,
        /** @var FHIRPositiveInt|null priority The priority of the coverage in the context of this account */
        public ?FHIRPositiveInt $priority = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
