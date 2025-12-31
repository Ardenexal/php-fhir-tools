<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description A species specific time during which consumption of animal product is not appropriate.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'AdministrableProductDefinition',
	elementPath: 'AdministrableProductDefinition.routeOfAdministration.targetSpecies.withdrawalPeriod',
	fhirVersion: 'R5',
)]
class FHIRAdministrableProductDefinitionRouteOfAdministrationTargetSpeciesWithdrawalPeriod extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept tissue The type of tissue for which the withdrawal period applies, e.g. meat, milk */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $tissue = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity value A value for the time */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $value = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string supportingInformation Extra information about the withdrawal period */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $supportingInformation = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
