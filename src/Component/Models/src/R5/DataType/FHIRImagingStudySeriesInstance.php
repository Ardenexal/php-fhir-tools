<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ImagingStudy.series.instance
 * @description A single SOP instance within the series, e.g. an image, or presentation state.
 */
class FHIRImagingStudySeriesInstance extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId uid DICOM SOP Instance UID */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRId $uid = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding sopClass DICOM class type */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding $sopClass = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt number The number of this instance in the series */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt $number = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string title Description of instance */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $title = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
