<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Each imaging selection includes one or more selected DICOM SOP instances.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ImagingSelection', elementPath: 'ImagingSelection.instance', fhirVersion: 'R5')]
class FHIRImagingSelectionInstance extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId uid DICOM SOP Instance UID */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId $uid = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt number DICOM Instance Number */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt $number = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding sopClass DICOM SOP Class UID */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding $sopClass = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> subset The selected subset of the SOP Instance */
		public array $subset = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImagingSelectionInstanceImageRegion2D> imageRegion2D A specific 2D region in a DICOM image / frame */
		public array $imageRegion2D = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImagingSelectionInstanceImageRegion3D> imageRegion3D A specific 3D region in a DICOM frame of reference */
		public array $imageRegion3D = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
