<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A single SOP instance within the series, e.g. an image, or presentation state.
 */
#[FHIRBackboneElement(parentResource: 'ImagingStudy', elementPath: 'ImagingStudy.series.instance', fhirVersion: 'R4')]
class FHIRImagingStudySeriesInstance extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null uid DICOM SOP Instance UID */
        #[NotBlank]
        public ?\FHIRId $uid = null,
        /** @var FHIRCoding|null sopClass DICOM class type */
        #[NotBlank]
        public ?\FHIRCoding $sopClass = null,
        /** @var FHIRUnsignedInt|null number The number of this instance in the series */
        public ?\FHIRUnsignedInt $number = null,
        /** @var FHIRString|string|null title Description of instance */
        public \FHIRString|string|null $title = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
