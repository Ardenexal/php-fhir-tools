<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A group or question item from the original questionnaire for which answers are provided.
 */
#[FHIRBackboneElement(parentResource: 'QuestionnaireResponse', elementPath: 'QuestionnaireResponse.item', fhirVersion: 'R4')]
class QuestionnaireResponseItem extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null linkId Pointer to specific item from Questionnaire */
        #[NotBlank]
        public StringPrimitive|string|null $linkId = null,
        /** @var UriPrimitive|null definition ElementDefinition - details for the item */
        public ?UriPrimitive $definition = null,
        /** @var StringPrimitive|string|null text Name for group or question text */
        public StringPrimitive|string|null $text = null,
        /** @var array<QuestionnaireResponseItemAnswer> answer The response(s) to the question */
        public array $answer = [],
        /** @var array<QuestionnaireResponseItem> item Nested questionnaire response items */
        public array $item = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
