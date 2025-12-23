<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MedicinalProductPharmaceutical.routeOfAdministration.targetSpecies
 * @description A species for which this route applies.
 */
class FHIRMedicinalProductPharmaceuticalRouteOfAdministrationTargetSpecies extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept code Coded expression for the species */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicinalProductPharmaceuticalRouteOfAdministrationTargetSpeciesWithdrawalPeriod> withdrawalPeriod A species specific time during which consumption of animal product is not appropriate */
		public array $withdrawalPeriod = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
