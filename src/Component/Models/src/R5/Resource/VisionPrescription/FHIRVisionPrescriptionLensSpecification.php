<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Contain the details of  the individual lens specifications and serves as the authorization for the fullfillment by certified professionals.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'VisionPrescription', elementPath: 'VisionPrescription.lensSpecification', fhirVersion: 'R5')]
class FHIRVisionPrescriptionLensSpecification extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept product Product to be supplied */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $product = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRVisionEyesType eye right | left */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRVisionEyesType $eye = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal sphere Power of the lens */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $sphere = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal cylinder Lens power for astigmatism */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $cylinder = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger axis Lens meridian which contain no power for astigmatism */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger $axis = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRVisionPrescriptionLensSpecificationPrism> prism Eye alignment compensation */
		public array $prism = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal add Added power for multifocal levels */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $add = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal power Contact lens power */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $power = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal backCurve Contact lens back curvature */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $backCurve = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal diameter Contact lens diameter */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal $diameter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity duration Lens wear duration */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity $duration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string color Color required */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $color = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string brand Brand required */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $brand = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation> note Notes for coatings */
		public array $note = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
