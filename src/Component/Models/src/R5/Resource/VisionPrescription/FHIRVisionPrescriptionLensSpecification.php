<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Contain the details of  the individual lens specifications and serves as the authorization for the fullfillment by certified professionals.
 */
#[FHIRBackboneElement(parentResource: 'VisionPrescription', elementPath: 'VisionPrescription.lensSpecification', fhirVersion: 'R5')]
class FHIRVisionPrescriptionLensSpecification extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null product Product to be supplied */
        #[NotBlank]
        public ?FHIRCodeableConcept $product = null,
        /** @var FHIRVisionEyesType|null eye right | left */
        #[NotBlank]
        public ?FHIRVisionEyesType $eye = null,
        /** @var FHIRDecimal|null sphere Power of the lens */
        public ?FHIRDecimal $sphere = null,
        /** @var FHIRDecimal|null cylinder Lens power for astigmatism */
        public ?FHIRDecimal $cylinder = null,
        /** @var FHIRInteger|null axis Lens meridian which contain no power for astigmatism */
        public ?FHIRInteger $axis = null,
        /** @var array<FHIRVisionPrescriptionLensSpecificationPrism> prism Eye alignment compensation */
        public array $prism = [],
        /** @var FHIRDecimal|null add Added power for multifocal levels */
        public ?FHIRDecimal $add = null,
        /** @var FHIRDecimal|null power Contact lens power */
        public ?FHIRDecimal $power = null,
        /** @var FHIRDecimal|null backCurve Contact lens back curvature */
        public ?FHIRDecimal $backCurve = null,
        /** @var FHIRDecimal|null diameter Contact lens diameter */
        public ?FHIRDecimal $diameter = null,
        /** @var FHIRQuantity|null duration Lens wear duration */
        public ?FHIRQuantity $duration = null,
        /** @var FHIRString|string|null color Color required */
        public FHIRString|string|null $color = null,
        /** @var FHIRString|string|null brand Brand required */
        public FHIRString|string|null $brand = null,
        /** @var array<FHIRAnnotation> note Notes for coatings */
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
