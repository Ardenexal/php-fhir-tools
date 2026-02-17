<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ClinicalImpression;

/**
 * @description Specific findings or diagnoses that were considered likely or relevant to ongoing treatment.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ClinicalImpression', elementPath: 'ClinicalImpression.finding', fhirVersion: 'R4')]
class ClinicalImpressionFinding extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept itemCodeableConcept What was found */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $itemCodeableConcept = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference itemReference What was found */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $itemReference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string basis Which investigations support finding */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $basis = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
