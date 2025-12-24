<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A group or question item from the original questionnaire for which answers are provided.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'QuestionnaireResponse', elementPath: 'QuestionnaireResponse.item', fhirVersion: 'R5')]
class FHIRQuestionnaireResponseItem extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null linkId Pointer to specific item from Questionnaire */
        #[NotBlank]
        public FHIRString|string|null $linkId = null,
        /** @var FHIRUri|null definition ElementDefinition - details for the item */
        public ?FHIRUri $definition = null,
        /** @var FHIRString|string|null text Name for group or question text */
        public FHIRString|string|null $text = null,
        /** @var array<FHIRQuestionnaireResponseItemAnswer> answer The response(s) to the question */
        public array $answer = [],
        /** @var array<FHIRQuestionnaireResponseItem> item Child items of group item */
        public array $item = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
