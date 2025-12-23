<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Immunization.protocolApplied
 * @description The protocol (set of recommendations) being followed by the provider who administered the dose.
 */
class FHIRImmunizationProtocolApplied extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string series Name of vaccine series */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $series = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference authority Who is responsible for publishing the recommendations */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference $authority = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> targetDisease Vaccine preventatable disease being targetted */
		public array $targetDisease = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string doseNumberX Dose number within series */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $doseNumberX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string seriesDosesX Recommended number of doses for immunity */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPositiveInt|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $seriesDosesX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
