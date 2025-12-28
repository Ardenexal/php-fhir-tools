<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The practitioner is not available or performing this role during this period of time due to the provided reason.
 */
#[FHIRBackboneElement(parentResource: 'PractitionerRole', elementPath: 'PractitionerRole.notAvailable', fhirVersion: 'R4B')]
class FHIRPractitionerRoleNotAvailable extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Reason presented to the user explaining why time not available */
        #[NotBlank]
        public \FHIRString|string|null $description = null,
        /** @var FHIRPeriod|null during Service not available from this date */
        public ?\FHIRPeriod $during = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
