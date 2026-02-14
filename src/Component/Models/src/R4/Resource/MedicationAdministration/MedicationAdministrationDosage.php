<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicationAdministration;

/**
 * @description Describes the medication dosage information details e.g. dose, rate, site, route, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MedicationAdministration', elementPath: 'MedicationAdministration.dosage', fhirVersion: 'R4')]
class MedicationAdministrationDosage extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string text Free text dosage instructions e.g. SIG */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $text = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept site Body site administered to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $site = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept route Path of substance into body */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $route = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept method How drug was administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity dose Amount of medication per dose */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $dose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity rateX Dose quantity per unit of time */
		public \Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity|null $rateX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
