<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;

/**
 * @description Categorical data indicating that an adverse event is associated in time to an immunization.
 */
#[FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.reaction', fhirVersion: 'R5')]
class FHIRImmunizationReaction extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRDateTime|null date When reaction started */
        public ?FHIRDateTime $date = null,
        /** @var FHIRCodeableReference|null manifestation Additional information on reaction */
        public ?FHIRCodeableReference $manifestation = null,
        /** @var FHIRBoolean|null reported Indicates self-reported reaction */
        public ?FHIRBoolean $reported = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
