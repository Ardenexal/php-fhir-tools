<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element MedicationAdministration.dosage
 * @description Describes the medication dosage information details e.g. dose, rate, site, route, etc.
 */
class FHIRMedicationAdministrationDosage extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string text Free text dosage instructions e.g. SIG */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $text = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept site Body site administered to */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $site = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept route Path of substance into body */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $route = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept method How drug was administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity dose Amount of medication per dose */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity $dose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity rateX Dose quantity per unit of time */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity|null $rateX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
