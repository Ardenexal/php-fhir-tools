<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;

/**
 * @description Details concerning the specimen collection.
 */
#[FHIRBackboneElement(parentResource: 'Specimen', elementPath: 'Specimen.collection', fhirVersion: 'R4B')]
class FHIRSpecimenCollection extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null collector Who collected the specimen */
        public ?FHIRReference $collector = null,
        /** @var FHIRDateTime|FHIRPeriod|null collectedX Collection time */
        public FHIRDateTime|FHIRPeriod|null $collectedX = null,
        /** @var FHIRDuration|null duration How long it took to collect specimen */
        public ?FHIRDuration $duration = null,
        /** @var FHIRQuantity|null quantity The quantity of specimen collected */
        public ?FHIRQuantity $quantity = null,
        /** @var FHIRCodeableConcept|null method Technique used to perform collection */
        public ?FHIRCodeableConcept $method = null,
        /** @var FHIRCodeableConcept|null bodySite Anatomical collection site */
        public ?FHIRCodeableConcept $bodySite = null,
        /** @var FHIRCodeableConcept|FHIRDuration|null fastingStatusX Whether or how long patient abstained from food and/or drink */
        public FHIRCodeableConcept|FHIRDuration|null $fastingStatusX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
