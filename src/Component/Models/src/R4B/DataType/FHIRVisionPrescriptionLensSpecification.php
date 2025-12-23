<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element VisionPrescription.lensSpecification
 * @description Contain the details of  the individual lens specifications and serves as the authorization for the fullfillment by certified professionals.
 */
class FHIRVisionPrescriptionLensSpecification extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept product Product to be supplied */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $product = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRVisionEyesType eye right | left */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRVisionEyesType $eye = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal sphere Power of the lens */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $sphere = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal cylinder Lens power for astigmatism */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $cylinder = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger axis Lens meridian which contain no power for astigmatism */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInteger $axis = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRVisionPrescriptionLensSpecificationPrism> prism Eye alignment compensation */
		public array $prism = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal add Added power for multifocal levels */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $add = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal power Contact lens power */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $power = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal backCurve Contact lens back curvature */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $backCurve = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal diameter Contact lens diameter */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal $diameter = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity duration Lens wear duration */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantity $duration = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string color Color required */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $color = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string brand Brand required */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $brand = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAnnotation> note Notes for coatings */
		public array $note = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
