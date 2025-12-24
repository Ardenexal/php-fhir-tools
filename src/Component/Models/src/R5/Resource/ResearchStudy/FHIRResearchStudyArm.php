<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Describes an expected sequence of events for one of the participants of a study.  E.g. Exposure to drug A, wash-out, exposure to drug B, wash-out, follow-up.
 */
#[FHIRBackboneElement(parentResource: 'ResearchStudy', elementPath: 'ResearchStudy.arm', fhirVersion: 'R4B')]
class FHIRResearchStudyArm extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Label for study arm */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var FHIRCodeableConcept|null type Categorization of study arm */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRString|string|null description Short explanation of study path */
        public FHIRString|string|null $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
