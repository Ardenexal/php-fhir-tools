<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Measure.group.stratifier.component
 * @description A component of the stratifier criteria for the measure report, specified as either the name of a valid CQL expression defined within a referenced library or a valid FHIR Resource Path.
 */
class FHIRMeasureGroupStratifierComponent extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept code Meaning of the stratifier component */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string description The human readable description of this stratifier component */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExpression criteria Component of how the measure should be stratified */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExpression $criteria = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
