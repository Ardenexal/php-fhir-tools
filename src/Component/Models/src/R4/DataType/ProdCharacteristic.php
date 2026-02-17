<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/ProdCharacteristic
 *
 * @description The marketing status describes the date when a medicinal product is actually put on the market or the date as of which it is no longer available.
 */
#[FHIRBackboneElement(parentResource: 'ProdCharacteristic', elementPath: 'ProdCharacteristic', fhirVersion: 'R4')]
class ProdCharacteristic extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Quantity|null height Where applicable, the height can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        public ?Quantity $height = null,
        /** @var Quantity|null width Where applicable, the width can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        public ?Quantity $width = null,
        /** @var Quantity|null depth Where applicable, the depth can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        public ?Quantity $depth = null,
        /** @var Quantity|null weight Where applicable, the weight can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        public ?Quantity $weight = null,
        /** @var Quantity|null nominalVolume Where applicable, the nominal volume can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        public ?Quantity $nominalVolume = null,
        /** @var Quantity|null externalDiameter Where applicable, the external diameter can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
        public ?Quantity $externalDiameter = null,
        /** @var StringPrimitive|string|null shape Where applicable, the shape can be specified An appropriate controlled vocabulary shall be used The term and the term identifier shall be used */
        public StringPrimitive|string|null $shape = null,
        /** @var array<StringPrimitive|string> color Where applicable, the color can be specified An appropriate controlled vocabulary shall be used The term and the term identifier shall be used */
        public array $color = [],
        /** @var array<StringPrimitive|string> imprint Where applicable, the imprint can be specified as text */
        public array $imprint = [],
        /** @var array<Attachment> image Where applicable, the image can be provided The format of the image attachment shall be specified by regional implementations */
        public array $image = [],
        /** @var CodeableConcept|null scoring Where applicable, the scoring can be specified An appropriate controlled vocabulary shall be used The term and the term identifier shall be used */
        public ?CodeableConcept $scoring = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
