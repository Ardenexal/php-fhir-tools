<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\VisionPrescription;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\VisionEyesType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Contain the details of  the individual lens specifications and serves as the authorization for the fullfillment by certified professionals.
 */
#[FHIRBackboneElement(parentResource: 'VisionPrescription', elementPath: 'VisionPrescription.lensSpecification', fhirVersion: 'R4')]
class VisionPrescriptionLensSpecification extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null product Product to be supplied */
        #[NotBlank]
        public ?CodeableConcept $product = null,
        /** @var VisionEyesType|null eye right | left */
        #[NotBlank]
        public ?VisionEyesType $eye = null,
        /** @var float|null sphere Power of the lens */
        public ?float $sphere = null,
        /** @var float|null cylinder Lens power for astigmatism */
        public ?float $cylinder = null,
        /** @var int|null axis Lens meridian which contain no power for astigmatism */
        public ?int $axis = null,
        /** @var array<VisionPrescriptionLensSpecificationPrism> prism Eye alignment compensation */
        public array $prism = [],
        /** @var float|null add Added power for multifocal levels */
        public ?float $add = null,
        /** @var float|null power Contact lens power */
        public ?float $power = null,
        /** @var float|null backCurve Contact lens back curvature */
        public ?float $backCurve = null,
        /** @var float|null diameter Contact lens diameter */
        public ?float $diameter = null,
        /** @var Quantity|null duration Lens wear duration */
        public ?Quantity $duration = null,
        /** @var StringPrimitive|string|null color Color required */
        public StringPrimitive|string|null $color = null,
        /** @var StringPrimitive|string|null brand Brand required */
        public StringPrimitive|string|null $brand = null,
        /** @var array<Annotation> note Notes for coatings */
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
