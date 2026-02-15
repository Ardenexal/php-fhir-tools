<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ImagingStudy;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Each study has one or more series of images or other content.
 */
#[FHIRBackboneElement(parentResource: 'ImagingStudy', elementPath: 'ImagingStudy.series', fhirVersion: 'R4')]
class ImagingStudySeries extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var IdPrimitive|null uid DICOM Series Instance UID for the series */
        #[NotBlank]
        public ?IdPrimitive $uid = null,
        /** @var UnsignedIntPrimitive|null number Numeric identifier of this series */
        public ?UnsignedIntPrimitive $number = null,
        /** @var Coding|null modality The modality of the instances in the series */
        #[NotBlank]
        public ?Coding $modality = null,
        /** @var StringPrimitive|string|null description A short human readable summary of the series */
        public StringPrimitive|string|null $description = null,
        /** @var UnsignedIntPrimitive|null numberOfInstances Number of Series Related Instances */
        public ?UnsignedIntPrimitive $numberOfInstances = null,
        /** @var array<Reference> endpoint Series access endpoint */
        public array $endpoint = [],
        /** @var Coding|null bodySite Body part examined */
        public ?Coding $bodySite = null,
        /** @var Coding|null laterality Body part laterality */
        public ?Coding $laterality = null,
        /** @var array<Reference> specimen Specimen imaged */
        public array $specimen = [],
        /** @var DateTimePrimitive|null started When the series started */
        public ?DateTimePrimitive $started = null,
        /** @var array<ImagingStudySeriesPerformer> performer Who performed the series */
        public array $performer = [],
        /** @var array<ImagingStudySeriesInstance> instance A single SOP instance from the series */
        public array $instance = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
