<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Group;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies the resource instances that are members of the group.
 */
#[FHIRBackboneElement(parentResource: 'Group', elementPath: 'Group.member', fhirVersion: 'R4')]
class GroupMember extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null entity Reference to the group member */
        #[NotBlank]
        public ?Reference $entity = null,
        /** @var Period|null period Period member belonged to the group */
        public ?Period $period = null,
        /** @var bool|null inactive If member is no longer in group */
        public ?bool $inactive = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
