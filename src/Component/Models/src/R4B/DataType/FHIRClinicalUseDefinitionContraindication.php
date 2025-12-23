<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ClinicalUseDefinition.contraindication
 * @description Specifics for when this is a contraindication.
 */
class FHIRClinicalUseDefinitionContraindication extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference diseaseSymptomProcedure The situation that is being documented as contraindicating against this item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference $diseaseSymptomProcedure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference diseaseStatus The status of the disease or symptom for the contraindication */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference $diseaseStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference> comorbidity A comorbidity (concurrent condition) or coinfection */
		public array $comorbidity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> indication The indication which this is a contraidication for */
		public array $indication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRClinicalUseDefinitionContraindicationOtherTherapy> otherTherapy Information about use of the product in relation to other therapies described as part of the contraindication */
		public array $otherTherapy = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
