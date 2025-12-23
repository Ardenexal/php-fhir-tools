<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Evidence.variableDefinition
 * @description Evidence variable such as population, exposure, or outcome.
 */
class FHIREvidenceVariableDefinition extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown description A text description or summary of the variable */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAnnotation> note Footnotes and/or explanatory notes */
		public array $note = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept variableRole population | subpopulation | exposure | referenceExposure | measuredVariable | confounder */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $variableRole = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference observed Definition of the actual variable related to the statistic(s) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $observed = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference intended Definition of the intended variable related to the Evidence */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $intended = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept directnessMatch low | moderate | high | exact */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $directnessMatch = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
