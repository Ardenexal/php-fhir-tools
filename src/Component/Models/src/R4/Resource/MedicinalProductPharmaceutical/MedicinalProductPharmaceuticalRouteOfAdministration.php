<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPharmaceutical;

/**
 * @description The path by which the pharmaceutical product is taken into or makes contact with the body.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'MedicinalProductPharmaceutical',
	elementPath: 'MedicinalProductPharmaceutical.routeOfAdministration',
	fhirVersion: 'R4',
)]
class MedicinalProductPharmaceuticalRouteOfAdministration extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept code Coded expression for the route */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity firstDose The first dose (dose quantity) administered in humans can be specified, for a product under investigation, using a numerical value and its unit of measurement */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $firstDose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity maxSingleDose The maximum single dose that can be administered as per the protocol of a clinical trial can be specified using a numerical value and its unit of measurement */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $maxSingleDose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity maxDosePerDay The maximum dose per day (maximum dose quantity to be administered in any one 24-h period) that can be administered as per the protocol referenced in the clinical trial authorisation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $maxDosePerDay = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio maxDosePerTreatmentPeriod The maximum dose per treatment period that can be administered as per the protocol referenced in the clinical trial authorisation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio $maxDosePerTreatmentPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration maxTreatmentPeriod The maximum treatment period during which an Investigational Medicinal Product can be administered as per the protocol referenced in the clinical trial authorisation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Duration $maxTreatmentPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPharmaceutical\MedicinalProductPharmaceuticalRouteOfAdministrationTargetSpecies> targetSpecies A species for which this route applies */
		public array $targetSpecies = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
