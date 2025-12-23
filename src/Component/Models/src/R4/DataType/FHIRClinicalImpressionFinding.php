<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ClinicalImpression.finding
 * @description Specific findings or diagnoses that were considered likely or relevant to ongoing treatment.
 */
class FHIRClinicalImpressionFinding extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept itemCodeableConcept What was found */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $itemCodeableConcept = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference itemReference What was found */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference $itemReference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string basis Which investigations support finding */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $basis = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
