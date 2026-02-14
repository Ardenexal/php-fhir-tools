<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ResearchStudy;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Describes an expected sequence of events for one of the participants of a study.  E.g. Exposure to drug A, wash-out, exposure to drug B, wash-out, follow-up.
 */
#[FHIRBackboneElement(parentResource: 'ResearchStudy', elementPath: 'ResearchStudy.arm', fhirVersion: 'R4')]
class ResearchStudyArm extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null name Label for study arm */
        #[NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var CodeableConcept|null type Categorization of study arm */
        public ?CodeableConcept $type = null,
        /** @var StringPrimitive|string|null description Short explanation of study path */
        public StringPrimitive|string|null $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
