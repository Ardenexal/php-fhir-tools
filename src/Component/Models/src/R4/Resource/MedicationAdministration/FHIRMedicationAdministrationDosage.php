<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Describes the medication dosage information details e.g. dose, rate, site, route, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationAdministration', elementPath: 'MedicationAdministration.dosage', fhirVersion: 'R4')]
class FHIRMedicationAdministrationDosage extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string text Free text dosage instructions e.g. SIG */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $text = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept site Body site administered to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $site = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept route Path of substance into body */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $route = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept method How drug was administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity dose Amount of medication per dose */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity $dose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity rateX Dose quantity per unit of time */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity|null $rateX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
