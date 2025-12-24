<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRDateTime;

/**
 * @description Information about the primary source(s) involved in validation.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'VerificationResult', elementPath: 'VerificationResult.primarySource', fhirVersion: 'R4')]
class FHIRVerificationResultPrimarySource extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null who Reference to the primary source */
        public ?FHIRReference $who = null,
        /** @var array<FHIRCodeableConcept> type Type of primary source (License Board; Primary Education; Continuing Education; Postal Service; Relationship owner; Registration Authority; legal source; issuing source; authoritative source) */
        public array $type = [],
        /** @var array<FHIRCodeableConcept> communicationMethod Method for exchanging information with the primary source */
        public array $communicationMethod = [],
        /** @var FHIRCodeableConcept|null validationStatus successful | failed | unknown */
        public ?FHIRCodeableConcept $validationStatus = null,
        /** @var FHIRDateTime|null validationDate When the target was validated against the primary source */
        public ?FHIRDateTime $validationDate = null,
        /** @var FHIRCodeableConcept|null canPushUpdates yes | no | undetermined */
        public ?FHIRCodeableConcept $canPushUpdates = null,
        /** @var array<FHIRCodeableConcept> pushTypeAvailable specific | any | source */
        public array $pushTypeAvailable = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
