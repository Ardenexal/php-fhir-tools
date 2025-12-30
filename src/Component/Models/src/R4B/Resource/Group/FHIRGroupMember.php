<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies the resource instances that are members of the group.
 */
#[FHIRBackboneElement(parentResource: 'Group', elementPath: 'Group.member', fhirVersion: 'R4B')]
class FHIRGroupMember extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null entity Reference to the group member */
        #[NotBlank]
        public ?FHIRReference $entity = null,
        /** @var FHIRPeriod|null period Period member belonged to the group */
        public ?FHIRPeriod $period = null,
        /** @var FHIRBoolean|null inactive If member is no longer in group */
        public ?FHIRBoolean $inactive = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
