<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element MedicinalProductPharmaceutical.routeOfAdministration.targetSpecies.withdrawalPeriod
 * @description A species specific time during which consumption of animal product is not appropriate.
 */
class FHIRMedicinalProductPharmaceuticalRouteOfAdministrationTargetSpeciesWithdrawalPeriod extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept tissue Coded expression for the type of tissue for which the withdrawal period applues, e.g. meat, milk */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $tissue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity value A value for the time */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string supportingInformation Extra information about the withdrawal period */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $supportingInformation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
