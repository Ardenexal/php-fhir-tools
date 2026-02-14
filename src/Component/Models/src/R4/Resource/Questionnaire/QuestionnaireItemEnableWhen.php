<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemOperatorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\TimePrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A constraint indicating that this item should only be enabled (displayed/allow answers to be captured) when the specified condition is true.
 */
#[FHIRBackboneElement(parentResource: 'Questionnaire', elementPath: 'Questionnaire.item.enableWhen', fhirVersion: 'R4')]
class QuestionnaireItemEnableWhen extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null question Question that determines whether item is enabled */
        #[NotBlank]
        public StringPrimitive|string|null $question = null,
        /** @var QuestionnaireItemOperatorType|null operator exists | = | != | > | < | >= | <= */
        #[NotBlank]
        public ?QuestionnaireItemOperatorType $operator = null,
        /** @var bool|float|int|DatePrimitive|DateTimePrimitive|TimePrimitive|StringPrimitive|string|Coding|Quantity|Reference|null answerX Value for question comparison based on operator */
        #[NotBlank]
        public bool|float|int|DatePrimitive|DateTimePrimitive|TimePrimitive|StringPrimitive|string|Coding|Quantity|Reference|null $answerX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
