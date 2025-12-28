<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description One of the permitted answers for a "choice" or "open-choice" question.
 */
#[FHIRBackboneElement(parentResource: 'Questionnaire', elementPath: 'Questionnaire.item.answerOption', fhirVersion: 'R4')]
class FHIRQuestionnaireItemAnswerOption extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRInteger|FHIRDate|FHIRTime|FHIRString|string|FHIRCoding|FHIRReference|null valueX Answer value */
        #[NotBlank]
        public \FHIRInteger|\FHIRDate|\FHIRTime|\FHIRString|string|\FHIRCoding|\FHIRReference|null $valueX = null,
        /** @var FHIRBoolean|null initialSelected Whether option is selected by default */
        public ?\FHIRBoolean $initialSelected = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
