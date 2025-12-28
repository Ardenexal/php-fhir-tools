<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @description Not available during this time due to provided reason.
 */
#[FHIRComplexType(typeName: 'Availability.notAvailableTime', fhirVersion: 'R5')]
class FHIRAvailabilityNotAvailableTime extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRString|string|null description Reason presented to the user explaining why time not available */
        public \FHIRString|string|null $description = null,
        /** @var FHIRPeriod|null during Service not available during this period */
        public ?\FHIRPeriod $during = null,
    ) {
        parent::__construct($id, $extension);
    }
}
