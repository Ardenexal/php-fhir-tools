<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Evidence variable such as population, exposure, or outcome.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.variableDefinition', fhirVersion: 'R5')]
class FHIREvidenceVariableDefinition extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description A text description or summary of the variable */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Footnotes and/or explanatory notes */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept variableRole population | subpopulation | exposure | referenceExposure | measuredVariable | confounder */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $variableRole = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference observed Definition of the actual variable related to the statistic(s) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $observed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference intended Definition of the intended variable related to the Evidence */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $intended = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept directnessMatch low | moderate | high | exact */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $directnessMatch = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
