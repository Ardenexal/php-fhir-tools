<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element SubstanceSpecification.code
 * @description Codes associated with the substance.
 */
class FHIRSubstanceSpecificationCode extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept code The specific code */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept status Status of the code assignment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime statusDate The date at which the code status is changed as part of the terminology maintenance */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime $statusDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string comment Any comment can be provided in this field, if necessary */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $comment = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> source Supporting literature */
		public array $source = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
