<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element SubstanceSpecification.property
 * @description General specifications for this substance, including how it is related to other substances.
 */
class FHIRSubstanceSpecificationProperty extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept category A category for this property, e.g. Physical, Chemical, Enzymatic */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $category = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept code Property type e.g. viscosity, pH, isoelectric point */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string parameters Parameters that were used in the measurement of a property (e.g. for viscosity: measured at 20C with a pH of 7.1) */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $parameters = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept definingSubstanceX A substance upon which a defining property depends (e.g. for solubility: in water, in alcohol) */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept|null $definingSubstanceX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string amountX Quantitative value for this property */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $amountX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
