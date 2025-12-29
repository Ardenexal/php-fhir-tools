<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description The path by which the product is taken into or makes contact with the body. In some regions this is referred to as the licenced or approved route. RouteOfAdministration cannot be used when the 'formOf' product already uses MedicinalProductDefinition.route (and vice versa).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'AdministrableProductDefinition',
	elementPath: 'AdministrableProductDefinition.routeOfAdministration',
	fhirVersion: 'R4B',
)]
class FHIRAdministrableProductDefinitionRouteOfAdministration extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept code Coded expression for the route */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity firstDose The first dose (dose quantity) administered can be specified for the product */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity $firstDose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity maxSingleDose The maximum single dose that can be administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity $maxSingleDose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity maxDosePerDay The maximum dose quantity to be administered in any one 24-h period */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity $maxDosePerDay = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio maxDosePerTreatmentPeriod The maximum dose per treatment period that can be administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio $maxDosePerTreatmentPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration maxTreatmentPeriod The maximum treatment period during which the product can be administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration $maxTreatmentPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAdministrableProductDefinitionRouteOfAdministrationTargetSpecies> targetSpecies A species for which this route applies */
		public array $targetSpecies = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
