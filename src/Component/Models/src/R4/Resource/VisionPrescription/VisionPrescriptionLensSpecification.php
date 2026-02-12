<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\VisionPrescription;

/**
 * @description Contain the details of  the individual lens specifications and serves as the authorization for the fullfillment by certified professionals.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'VisionPrescription', elementPath: 'VisionPrescription.lensSpecification', fhirVersion: 'R4')]
class VisionPrescriptionLensSpecification extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept product Product to be supplied */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $product = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\VisionEyesType eye right | left */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\VisionEyesType $eye = null,
		/** @var null|float sphere Power of the lens */
		public ?float $sphere = null,
		/** @var null|float cylinder Lens power for astigmatism */
		public ?float $cylinder = null,
		/** @var null|int axis Lens meridian which contain no power for astigmatism */
		public ?int $axis = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\VisionPrescription\VisionPrescriptionLensSpecificationPrism> prism Eye alignment compensation */
		public array $prism = [],
		/** @var null|float add Added power for multifocal levels */
		public ?float $add = null,
		/** @var null|float power Contact lens power */
		public ?float $power = null,
		/** @var null|float backCurve Contact lens back curvature */
		public ?float $backCurve = null,
		/** @var null|float diameter Contact lens diameter */
		public ?float $diameter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity duration Lens wear duration */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $duration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string color Color required */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $color = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string brand Brand required */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $brand = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation> note Notes for coatings */
		public array $note = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
