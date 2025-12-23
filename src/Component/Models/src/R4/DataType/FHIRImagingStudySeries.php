<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element ImagingStudy.series
 * @description Each study has one or more series of images or other content.
 */
class FHIRImagingStudySeries extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId uid DICOM Series Instance UID for the series */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRId $uid = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt number Numeric identifier of this series */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt $number = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding modality The modality of the instances in the series */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding $modality = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string description A short human readable summary of the series */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt numberOfInstances Number of Series Related Instances */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUnsignedInt $numberOfInstances = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> endpoint Series access endpoint */
		public array $endpoint = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding bodySite Body part examined */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding $bodySite = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding laterality Body part laterality */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCoding $laterality = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> specimen Specimen imaged */
		public array $specimen = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime started When the series started */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRDateTime $started = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRImagingStudySeriesPerformer> performer Who performed the series */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRImagingStudySeriesInstance> instance A single SOP instance from the series */
		public array $instance = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
