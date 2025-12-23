<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element MedicinalProductPharmaceutical.routeOfAdministration
 * @description The path by which the pharmaceutical product is taken into or makes contact with the body.
 */
class FHIRMedicinalProductPharmaceuticalRouteOfAdministration extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept code Coded expression for the route */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity firstDose The first dose (dose quantity) administered in humans can be specified, for a product under investigation, using a numerical value and its unit of measurement */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $firstDose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity maxSingleDose The maximum single dose that can be administered as per the protocol of a clinical trial can be specified using a numerical value and its unit of measurement */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $maxSingleDose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity maxDosePerDay The maximum dose per day (maximum dose quantity to be administered in any one 24-h period) that can be administered as per the protocol referenced in the clinical trial authorisation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $maxDosePerDay = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRatio maxDosePerTreatmentPeriod The maximum dose per treatment period that can be administered as per the protocol referenced in the clinical trial authorisation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRatio $maxDosePerTreatmentPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration maxTreatmentPeriod The maximum treatment period during which an Investigational Medicinal Product can be administered as per the protocol referenced in the clinical trial authorisation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration $maxTreatmentPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMedicinalProductPharmaceuticalRouteOfAdministrationTargetSpecies> targetSpecies A species for which this route applies */
		public array $targetSpecies = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
