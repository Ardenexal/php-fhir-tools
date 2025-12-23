<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ClinicalUseDefinition.contraindication
 * @description Specifics for when this is a contraindication.
 */
class FHIRClinicalUseDefinitionContraindication extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference diseaseSymptomProcedure The situation that is being documented as contraindicating against this item */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference $diseaseSymptomProcedure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference diseaseStatus The status of the disease or symptom for the contraindication */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference $diseaseStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> comorbidity A comorbidity (concurrent condition) or coinfection */
		public array $comorbidity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> indication The indication which this is a contraidication for */
		public array $indication = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExpression applicability An expression that returns true or false, indicating whether the indication is applicable or not, after having applied its other elements */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExpression $applicability = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRClinicalUseDefinitionContraindicationOtherTherapy> otherTherapy Information about use of the product in relation to other therapies described as part of the contraindication */
		public array $otherTherapy = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
