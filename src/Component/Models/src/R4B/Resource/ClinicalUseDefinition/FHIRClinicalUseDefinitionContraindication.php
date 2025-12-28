<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Specifics for when this is a contraindication.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ClinicalUseDefinition', elementPath: 'ClinicalUseDefinition.contraindication', fhirVersion: 'R4B')]
class FHIRClinicalUseDefinitionContraindication extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference diseaseSymptomProcedure The situation that is being documented as contraindicating against this item */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference $diseaseSymptomProcedure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference diseaseStatus The status of the disease or symptom for the contraindication */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference $diseaseStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference> comorbidity A comorbidity (concurrent condition) or coinfection */
		public array $comorbidity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference> indication The indication which this is a contraidication for */
		public array $indication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRClinicalUseDefinitionContraindicationOtherTherapy> otherTherapy Information about use of the product in relation to other therapies described as part of the contraindication */
		public array $otherTherapy = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
