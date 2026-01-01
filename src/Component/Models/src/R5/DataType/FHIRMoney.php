<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Money
 * @description An amount of economic utility in some recognized currency.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Money', fhirVersion: 'R5')]
class FHIRMoney extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal value Numerical value (with implicit precision) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCurrenciesType currency ISO 4217 Currency Code */
		public ?FHIRCurrenciesType $currency = null,
	) {
		parent::__construct($id, $extension);
	}
}
