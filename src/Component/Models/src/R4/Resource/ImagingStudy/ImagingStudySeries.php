<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImagingStudy;

/**
 * @description Each study has one or more series of images or other content.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ImagingStudy', elementPath: 'ImagingStudy.series', fhirVersion: 'R4')]
class ImagingStudySeries extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive uid DICOM Series Instance UID for the series */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $uid = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive number Numeric identifier of this series */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive $number = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding modality The modality of the instances in the series */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $modality = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description A short human readable summary of the series */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive numberOfInstances Number of Series Related Instances */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive $numberOfInstances = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> endpoint Series access endpoint */
		public array $endpoint = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding bodySite Body part examined */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $bodySite = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding laterality Body part laterality */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $laterality = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> specimen Specimen imaged */
		public array $specimen = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive started When the series started */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $started = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ImagingStudy\ImagingStudySeriesPerformer> performer Who performed the series */
		public array $performer = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ImagingStudy\ImagingStudySeriesInstance> instance A single SOP instance from the series */
		public array $instance = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
