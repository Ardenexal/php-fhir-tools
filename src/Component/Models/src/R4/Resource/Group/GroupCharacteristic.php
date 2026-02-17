<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Group;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies traits whose presence r absence is shared by members of the group.
 */
#[FHIRBackboneElement(parentResource: 'Group', elementPath: 'Group.characteristic', fhirVersion: 'R4')]
class GroupCharacteristic extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Kind of characteristic */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var CodeableConcept|bool|Quantity|Range|Reference|null valueX Value held by characteristic */
        #[NotBlank]
        public CodeableConcept|bool|Quantity|Range|Reference|null $valueX = null,
        /** @var bool|null exclude Group includes or excludes */
        #[NotBlank]
        public ?bool $exclude = null,
        /** @var Period|null period Period over which characteristic is tested */
        public ?Period $period = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
