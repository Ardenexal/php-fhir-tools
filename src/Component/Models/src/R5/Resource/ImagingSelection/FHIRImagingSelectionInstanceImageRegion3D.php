<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Each imaging selection might includes a 3D image region, specified by a region type and a set of 3D coordinates.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ImagingSelection', elementPath: 'ImagingSelection.instance.imageRegion3D', fhirVersion: 'R5')]
class FHIRImagingSelectionInstanceImageRegion3D extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRImagingSelection3DGraphicTypeType regionType point | multipoint | polyline | polygon | ellipse | ellipsoid */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRImagingSelection3DGraphicTypeType $regionType = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal> coordinate Specifies the coordinates that define the image region */
		public array $coordinate = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
