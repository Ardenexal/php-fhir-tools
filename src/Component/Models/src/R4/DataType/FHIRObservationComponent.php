<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Observation.component
 * @description Some observations have multiple component observations.  These component observations are expressed as separate code value pairs that share the same attributes.  Examples include systolic and diastolic component observations for blood pressure measurement and multiple component observations for genetics observations.
 */
class FHIRObservationComponent extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept code Type of component observation (code / type) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSampledData|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod valueX Actual component result */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRQuantity|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBoolean|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInteger|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRange|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRRatio|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRSampledData|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPeriod|null $valueX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept dataAbsentReason Why the component result is missing */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $dataAbsentReason = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> interpretation High, low, normal, etc. */
		public array $interpretation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRObservationReferenceRange> referenceRange Provides guide for interpretation of component result */
		public array $referenceRange = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
