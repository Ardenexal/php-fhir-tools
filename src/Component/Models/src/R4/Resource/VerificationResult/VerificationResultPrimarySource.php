<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\VerificationResult;

/**
 * @description Information about the primary source(s) involved in validation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'VerificationResult', elementPath: 'VerificationResult.primarySource', fhirVersion: 'R4')]
class VerificationResultPrimarySource extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference who Reference to the primary source */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $who = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> type Type of primary source (License Board; Primary Education; Continuing Education; Postal Service; Relationship owner; Registration Authority; legal source; issuing source; authoritative source) */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> communicationMethod Method for exchanging information with the primary source */
		public array $communicationMethod = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept validationStatus successful | failed | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $validationStatus = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive validationDate When the target was validated against the primary source */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $validationDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept canPushUpdates yes | no | undetermined */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $canPushUpdates = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> pushTypeAvailable specific | any | source */
		public array $pushTypeAvailable = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
