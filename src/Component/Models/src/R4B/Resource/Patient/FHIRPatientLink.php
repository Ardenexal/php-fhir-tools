<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Link to another patient resource that concerns the same actual patient.
 */
#[FHIRBackboneElement(parentResource: 'Patient', elementPath: 'Patient.link', fhirVersion: 'R4B')]
class FHIRPatientLink extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null other The other patient or related person resource that the link refers to */
        #[NotBlank]
        public ?FHIRReference $other = null,
        /** @var FHIRLinkTypeType|null type replaced-by | replaces | refer | seealso */
        #[NotBlank]
        public ?FHIRLinkTypeType $type = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
