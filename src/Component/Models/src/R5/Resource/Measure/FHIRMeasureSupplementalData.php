<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The supplemental data criteria for the measure report, specified as either the name of a valid CQL expression within a referenced library, or a valid FHIR Resource Path.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Measure', elementPath: 'Measure.supplementalData', fhirVersion: 'R5')]
class FHIRMeasureSupplementalData extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string linkId Unique id for supplementalData in measure */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $linkId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept code Meaning of the supplemental data */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $code = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> usage supplemental-data | risk-adjustment-factor */
		public array $usage = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown description The human readable description of this supplemental data */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression criteria Expression describing additional data to be reported */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExpression $criteria = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
