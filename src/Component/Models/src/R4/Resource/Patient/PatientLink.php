<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Patient;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\LinkTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Link to another patient resource that concerns the same actual patient.
 */
#[FHIRBackboneElement(parentResource: 'Patient', elementPath: 'Patient.link', fhirVersion: 'R4')]
class PatientLink extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null other The other patient or related person resource that the link refers to */
        #[NotBlank]
        public ?Reference $other = null,
        /** @var LinkTypeType|null type replaced-by | replaces | refer | seealso */
        #[NotBlank]
        public ?LinkTypeType $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
