<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ImagingSelection.instance
 * @description Each imaging selection includes one or more selected DICOM SOP instances.
 */
class FHIRImagingSelectionInstance extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt number DICOM Instance Number */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt $number = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding sopClass DICOM SOP Class UID */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding $sopClass = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string> subset The selected subset of the SOP Instance */
		public array $subset = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImagingSelectionInstanceImageRegion2D> imageRegion2D A specific 2D region in a DICOM image / frame */
		public array $imageRegion2D = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImagingSelectionInstanceImageRegion3D> imageRegion3D A specific 3D region in a DICOM frame of reference */
		public array $imageRegion3D = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
