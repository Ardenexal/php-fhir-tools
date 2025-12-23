<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element ClinicalUseDefinition.indication
 * @description Specifics for when this is an indication.
 */
class FHIRClinicalUseDefinitionIndication extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference diseaseSymptomProcedure The situation that is being documented as an indicaton for this item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference $diseaseSymptomProcedure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference diseaseStatus The status of the disease or symptom for the indication */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference $diseaseStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference> comorbidity A comorbidity or coinfection as part of the indication */
		public array $comorbidity = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference intendedEffect The intended effect, aim or strategy to be achieved */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableReference $intendedEffect = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string durationX Timing or duration information */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $durationX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference> undesirableEffect An unwanted side effect or negative outcome of the subject of this resource when being used for this indication */
		public array $undesirableEffect = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRClinicalUseDefinitionContraindicationOtherTherapy> otherTherapy The use of the medicinal product in relation to other therapies described as part of the indication */
		public array $otherTherapy = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
