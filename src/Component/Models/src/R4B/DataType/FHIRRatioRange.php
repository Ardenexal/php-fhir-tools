<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/RatioRange
 * @description A range of ratios expressed as a low and high numerator and a denominator.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'RatioRange', fhirVersion: 'R4B')]
class FHIRRatioRange extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity lowNumerator Low Numerator limit */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $lowNumerator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity highNumerator High Numerator limit */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $highNumerator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity denominator Denominator value */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $denominator = null,
	) {
		parent::__construct($id, $extension);
	}
}
