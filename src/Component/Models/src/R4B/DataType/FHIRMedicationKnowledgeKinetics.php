<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element MedicationKnowledge.kinetics
 * @description The time course of drug absorption, distribution, metabolism and excretion of a medication from the body.
 */
class FHIRMedicationKnowledgeKinetics extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity> areaUnderCurve The drug concentration measured at certain discrete points in time */
		public array $areaUnderCurve = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity> lethalDose50 The median lethal dose of a drug */
		public array $lethalDose50 = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration halfLifePeriod Time required for concentration in the body to decrease by half */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDuration $halfLifePeriod = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
