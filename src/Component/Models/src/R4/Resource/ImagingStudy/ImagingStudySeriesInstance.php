<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImagingStudy;

/**
 * @description A single SOP instance within the series, e.g. an image, or presentation state.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ImagingStudy', elementPath: 'ImagingStudy.series.instance', fhirVersion: 'R4')]
class ImagingStudySeriesInstance extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive uid DICOM SOP Instance UID */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $uid = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding sopClass DICOM class type */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $sopClass = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive number The number of this instance in the series */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive $number = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string title Description of instance */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $title = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
