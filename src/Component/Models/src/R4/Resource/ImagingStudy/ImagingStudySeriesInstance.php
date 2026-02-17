<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImagingStudy;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A single SOP instance within the series, e.g. an image, or presentation state.
 */
#[FHIRBackboneElement(parentResource: 'ImagingStudy', elementPath: 'ImagingStudy.series.instance', fhirVersion: 'R4')]
class ImagingStudySeriesInstance extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var IdPrimitive|null uid DICOM SOP Instance UID */
        #[NotBlank]
        public ?IdPrimitive $uid = null,
        /** @var Coding|null sopClass DICOM class type */
        #[NotBlank]
        public ?Coding $sopClass = null,
        /** @var UnsignedIntPrimitive|null number The number of this instance in the series */
        public ?UnsignedIntPrimitive $number = null,
        /** @var StringPrimitive|string|null title Description of instance */
        public StringPrimitive|string|null $title = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
