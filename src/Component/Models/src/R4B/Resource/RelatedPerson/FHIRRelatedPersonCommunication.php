<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A language which may be used to communicate with about the patient's health.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'RelatedPerson', elementPath: 'RelatedPerson.communication', fhirVersion: 'R4B')]
class FHIRRelatedPersonCommunication extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null language The language which can be used to communicate with the patient about his or her health */
        #[NotBlank]
        public ?FHIRCodeableConcept $language = null,
        /** @var FHIRBoolean|null preferred Language preference indicator */
        public ?FHIRBoolean $preferred = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
