<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Each study has one or more series of images or other content.
 */
#[FHIRBackboneElement(parentResource: 'ImagingStudy', elementPath: 'ImagingStudy.series', fhirVersion: 'R5')]
class FHIRImagingStudySeries extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null uid DICOM Series Instance UID for the series */
        #[NotBlank]
        public ?\FHIRId $uid = null,
        /** @var FHIRUnsignedInt|null number Numeric identifier of this series */
        public ?\FHIRUnsignedInt $number = null,
        /** @var FHIRCodeableConcept|null modality The modality used for this series */
        #[NotBlank]
        public ?\FHIRCodeableConcept $modality = null,
        /** @var FHIRString|string|null description A short human readable summary of the series */
        public \FHIRString|string|null $description = null,
        /** @var FHIRUnsignedInt|null numberOfInstances Number of Series Related Instances */
        public ?\FHIRUnsignedInt $numberOfInstances = null,
        /** @var array<FHIRReference> endpoint Series access endpoint */
        public array $endpoint = [],
        /** @var FHIRCodeableReference|null bodySite Body part examined */
        public ?\FHIRCodeableReference $bodySite = null,
        /** @var FHIRCodeableConcept|null laterality Body part laterality */
        public ?\FHIRCodeableConcept $laterality = null,
        /** @var array<FHIRReference> specimen Specimen imaged */
        public array $specimen = [],
        /** @var FHIRDateTime|null started When the series started */
        public ?\FHIRDateTime $started = null,
        /** @var array<FHIRImagingStudySeriesPerformer> performer Who performed the series */
        public array $performer = [],
        /** @var array<FHIRImagingStudySeriesInstance> instance A single SOP instance from the series */
        public array $instance = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
