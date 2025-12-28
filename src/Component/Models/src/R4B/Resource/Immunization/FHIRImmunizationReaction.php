<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;

/**
 * @description Categorical data indicating that an adverse event is associated in time to an immunization.
 */
#[FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.reaction', fhirVersion: 'R4B')]
class FHIRImmunizationReaction extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
        /** @var FHIRReference|null detail Additional information on reaction */
        public ?FHIRReference $detail = null,
        /** @var FHIRBoolean|null reported Indicates self-reported reaction */
        public ?FHIRBoolean $reported = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
