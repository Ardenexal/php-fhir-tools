<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductPharmaceutical;

/**
 * @description A species specific time during which consumption of animal product is not appropriate.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'MedicinalProductPharmaceutical',
	elementPath: 'MedicinalProductPharmaceutical.routeOfAdministration.targetSpecies.withdrawalPeriod',
	fhirVersion: 'R4',
)]
class MedicinalProductPharmaceuticalRouteOfAdministrationTargetSpeciesWithdrawalPeriod extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept tissue Coded expression for the type of tissue for which the withdrawal period applues, e.g. meat, milk */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $tissue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity value A value for the time */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string supportingInformation Extra information about the withdrawal period */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $supportingInformation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
