<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element AdministrableProductDefinition.routeOfAdministration
 * @description The path by which the product is taken into or makes contact with the body. In some regions this is referred to as the licenced or approved route. RouteOfAdministration cannot be used when the 'formOf' product already uses MedicinalProductDefinition.route (and vice versa).
 */
class FHIRAdministrableProductDefinitionRouteOfAdministration extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity firstDose The first dose (dose quantity) administered can be specified for the product */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $firstDose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity maxSingleDose The maximum single dose that can be administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $maxSingleDose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity maxDosePerDay The maximum dose quantity to be administered in any one 24-h period */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $maxDosePerDay = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRatio maxDosePerTreatmentPeriod The maximum dose per treatment period that can be administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRatio $maxDosePerTreatmentPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration maxTreatmentPeriod The maximum treatment period during which the product can be administered */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration $maxTreatmentPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAdministrableProductDefinitionRouteOfAdministrationTargetSpecies> targetSpecies A species for which this route applies */
		public array $targetSpecies = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
