<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Receiver Operator Characteristic (ROC) Curve  to give sensitivity/specificity tradeoff.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.quality.roc', fhirVersion: 'R4')]
class FHIRMolecularSequenceQualityRoc extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger> score Genotype quality score */
		public array $score = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger> numTP Roc score true positive numbers */
		public array $numTP = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger> numFP Roc score false positive numbers */
		public array $numFP = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger> numFN Roc score false negative numbers */
		public array $numFN = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal> precision Precision of the GQ score */
		public array $precision = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal> sensitivity Sensitivity of the GQ score */
		public array $sensitivity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDecimal> fMeasure FScore of the GQ score */
		public array $fMeasure = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
