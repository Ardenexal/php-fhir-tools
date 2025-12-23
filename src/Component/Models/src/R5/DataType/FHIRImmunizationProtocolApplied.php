<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Immunization.protocolApplied
 * @description The protocol (set of recommendations) being followed by the provider who administered the dose.
 */
class FHIRImmunizationProtocolApplied extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string series Name of vaccine series */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $series = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference authority Who is responsible for publishing the recommendations */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $authority = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> targetDisease Vaccine preventatable disease being targeted */
		public array $targetDisease = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string doseNumber Dose number within series */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $doseNumber = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string seriesDoses Recommended number of doses for immunity */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $seriesDoses = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
