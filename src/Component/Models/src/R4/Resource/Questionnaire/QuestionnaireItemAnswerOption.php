<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description One of the permitted answers for a "choice" or "open-choice" question.
 */
#[FHIRBackboneElement(parentResource: 'Questionnaire', elementPath: 'Questionnaire.item.answerOption', fhirVersion: 'R4')]
class QuestionnaireItemAnswerOption extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var int|DatePrimitive|TimePrimitive|StringPrimitive|string|Coding|Reference|null valueX Answer value */
        #[NotBlank]
        public int|DatePrimitive|TimePrimitive|StringPrimitive|string|Coding|Reference|null $valueX = null,
        /** @var bool|null initialSelected Whether option is selected by default */
        public ?bool $initialSelected = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
