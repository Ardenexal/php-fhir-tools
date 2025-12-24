<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A property value for this concept.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CodeSystem', elementPath: 'CodeSystem.concept.property', fhirVersion: 'R4')]
class FHIRCodeSystemConceptProperty extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCode|null code Reference to CodeSystem.property.code */
        #[NotBlank]
        public ?FHIRCode $code = null,
        /** @var FHIRCode|FHIRCoding|FHIRString|string|FHIRInteger|FHIRBoolean|FHIRDateTime|FHIRDecimal|null valueX Value of the property for this concept */
        #[NotBlank]
        public FHIRCode|FHIRCoding|FHIRString|string|FHIRInteger|FHIRBoolean|FHIRDateTime|FHIRDecimal|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
