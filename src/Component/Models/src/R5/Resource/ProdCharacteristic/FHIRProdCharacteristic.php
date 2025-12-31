<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/ProdCharacteristic
 * @description The marketing status describes the date when a medicinal product is actually put on the market or the date as of which it is no longer available.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ProdCharacteristic', elementPath: 'ProdCharacteristic', fhirVersion: 'R5')]
class FHIRProdCharacteristic extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity height Where applicable, the height can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $height = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity width Where applicable, the width can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $width = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity depth Where applicable, the depth can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $depth = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity weight Where applicable, the weight can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $weight = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity nominalVolume Where applicable, the nominal volume can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $nominalVolume = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity externalDiameter Where applicable, the external diameter can be specified using a numerical value and its unit of measurement The unit of measurement shall be specified in accordance with ISO 11240 and the resulting terminology The symbol and the symbol identifier shall be used */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $externalDiameter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string shape Where applicable, the shape can be specified An appropriate controlled vocabulary shall be used The term and the term identifier shall be used */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $shape = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> color Where applicable, the color can be specified An appropriate controlled vocabulary shall be used The term and the term identifier shall be used */
		public array $color = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> imprint Where applicable, the imprint can be specified as text */
		public array $imprint = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment> image Where applicable, the image can be provided The format of the image attachment shall be specified by regional implementations */
		public array $image = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept scoring Where applicable, the scoring can be specified An appropriate controlled vocabulary shall be used The term and the term identifier shall be used */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $scoring = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
