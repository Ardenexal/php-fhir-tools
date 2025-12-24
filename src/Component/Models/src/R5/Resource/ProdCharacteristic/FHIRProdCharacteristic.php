<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ProdCharacteristic
 *
 * @description The marketing status describes the date when a medicinal product is actually put on the market or the date as of which it is no longer available.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ProdCharacteristic', elementPath: 'ProdCharacteristic', fhirVersion: 'R5')]
class FHIRProdCharacteristic extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRQuantity|null height Where applicable, the height can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        public ?FHIRQuantity $height = null,
        /** @var FHIRQuantity|null width Where applicable, the width can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        public ?FHIRQuantity $width = null,
        /** @var FHIRQuantity|null depth Where applicable, the depth can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        public ?FHIRQuantity $depth = null,
        /** @var FHIRQuantity|null weight Where applicable, the weight can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        public ?FHIRQuantity $weight = null,
        /** @var FHIRQuantity|null nominalVolume Where applicable, the nominal volume can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        public ?FHIRQuantity $nominalVolume = null,
        /** @var FHIRQuantity|null externalDiameter Where applicable, the external diameter can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        public ?FHIRQuantity $externalDiameter = null,
        /** @var FHIRString|string|null shape Where applicable, the shape can be specified An appropriate controlled vocabulary shall be used The term and the term identifier shall be used */
        public FHIRString|string|null $shape = null,
        /** @var array<FHIRString|string> color Where applicable, the color can be specified An appropriate controlled vocabulary shall be used The term and the term identifier shall be used */
        public array $color = [],
        /** @var array<FHIRString|string> imprint Where applicable, the imprint can be specified as text */
        public array $imprint = [],
        /** @var array<FHIRAttachment> image Where applicable, the image can be provided The format of the image attachment shall be specified by regional implementations */
        public array $image = [],
        /** @var FHIRCodeableConcept|null scoring Where applicable, the scoring can be specified An appropriate controlled vocabulary shall be used The term and the term identifier shall be used */
        public ?FHIRCodeableConcept $scoring = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
