<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\PractitionerRole;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The practitioner is not available or performing this role during this period of time due to the provided reason.
 */
#[FHIRBackboneElement(parentResource: 'PractitionerRole', elementPath: 'PractitionerRole.notAvailable', fhirVersion: 'R4')]
class PractitionerRoleNotAvailable extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null description Reason presented to the user explaining why time not available */
        #[NotBlank]
        public StringPrimitive|string|null $description = null,
        /** @var Period|null during Service not available from this date */
        public ?Period $during = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
