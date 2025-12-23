<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element ImagingSelection.instance.imageRegion2D
 * @description Each imaging selection instance or frame list might includes an image region, specified by a region type and a set of 2D coordinates.
 *        If the parent imagingSelection.instance contains a subset element of type frame, the image region applies to all frames in the subset list.
 */
class FHIRImagingSelectionInstanceImageRegion2D extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImagingSelection2DGraphicTypeType regionType point | polyline | interpolated | circle | ellipse */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRImagingSelection2DGraphicTypeType $regionType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDecimal> coordinate Specifies the coordinates that define the image region */
		public array $coordinate = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
