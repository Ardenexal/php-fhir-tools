<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRTime;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Parameters to the transform.
 */
#[FHIRBackboneElement(parentResource: 'StructureMap', elementPath: 'StructureMap.group.rule.target.parameter', fhirVersion: 'R5')]
class FHIRStructureMapGroupRuleTargetParameter extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|FHIRString|string|FHIRBoolean|FHIRInteger|FHIRDecimal|FHIRDate|FHIRTime|FHIRDateTime|null valueX Parameter value - variable or literal */
        #[NotBlank]
        public FHIRId|FHIRString|string|FHIRBoolean|FHIRInteger|FHIRDecimal|FHIRDate|FHIRTime|FHIRDateTime|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
