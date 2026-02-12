<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence;

/**
 * @description Receiver Operator Characteristic (ROC) Curve  to give sensitivity/specificity tradeoff.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.quality.roc', fhirVersion: 'R4')]
class MolecularSequenceQualityRoc extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<int> score Genotype quality score */
		public array $score = [],
		/** @var  array<int> numTP Roc score true positive numbers */
		public array $numTP = [],
		/** @var  array<int> numFP Roc score false positive numbers */
		public array $numFP = [],
		/** @var  array<int> numFN Roc score false negative numbers */
		public array $numFN = [],
		/** @var  array<float> precision Precision of the GQ score */
		public array $precision = [],
		/** @var  array<float> sensitivity Sensitivity of the GQ score */
		public array $sensitivity = [],
		/** @var  array<float> fMeasure FScore of the GQ score */
		public array $fMeasure = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
