<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/MonetaryComponent
 * @description Availability data for an {item}.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'MonetaryComponent', fhirVersion: 'R5')]
class FHIRMonetaryComponent extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPriceComponentTypeType type base | surcharge | deduction | discount | tax | informational */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPriceComponentTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept code Codes may be used to differentiate between kinds of taxes, surcharges, discounts etc. */
		public ?FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal factor Factor used for calculating this component */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $factor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMoney amount Explicit value amount to be used */
		public ?FHIRMoney $amount = null,
	) {
		parent::__construct($id, $extension);
	}
}
