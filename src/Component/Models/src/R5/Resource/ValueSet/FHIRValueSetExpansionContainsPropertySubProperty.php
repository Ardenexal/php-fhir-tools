<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A subproperty value for this concept.
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.expansion.contains.property.subProperty', fhirVersion: 'R5')]
class FHIRValueSetExpansionContainsPropertySubProperty extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Reference to ValueSet.expansion.property.code */
        #[NotBlank]
        public ?FHIRCode $code = null,
        /** @var FHIRCode|FHIRCoding|FHIRString|string|FHIRInteger|FHIRBoolean|FHIRDateTime|FHIRDecimal|null valueX Value of the subproperty for this concept */
        #[NotBlank]
        public FHIRCode|FHIRCoding|FHIRString|string|FHIRInteger|FHIRBoolean|FHIRDateTime|FHIRDecimal|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
