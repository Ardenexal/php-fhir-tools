<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\VerificationResult;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;

/**
 * @description Information about the primary source(s) involved in validation.
 */
#[FHIRBackboneElement(parentResource: 'VerificationResult', elementPath: 'VerificationResult.primarySource', fhirVersion: 'R4')]
class VerificationResultPrimarySource extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null who Reference to the primary source */
        public ?Reference $who = null,
        /** @var array<CodeableConcept> type Type of primary source (License Board; Primary Education; Continuing Education; Postal Service; Relationship owner; Registration Authority; legal source; issuing source; authoritative source) */
        public array $type = [],
        /** @var array<CodeableConcept> communicationMethod Method for exchanging information with the primary source */
        public array $communicationMethod = [],
        /** @var CodeableConcept|null validationStatus successful | failed | unknown */
        public ?CodeableConcept $validationStatus = null,
        /** @var DateTimePrimitive|null validationDate When the target was validated against the primary source */
        public ?DateTimePrimitive $validationDate = null,
        /** @var CodeableConcept|null canPushUpdates yes | no | undetermined */
        public ?CodeableConcept $canPushUpdates = null,
        /** @var array<CodeableConcept> pushTypeAvailable specific | any | source */
        public array $pushTypeAvailable = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
