<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The respondent's answer(s) to the question.
 */
#[FHIRBackboneElement(parentResource: 'QuestionnaireResponse', elementPath: 'QuestionnaireResponse.item.answer', fhirVersion: 'R4')]
class FHIRQuestionnaireResponseItemAnswer extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|FHIRDecimal|FHIRInteger|FHIRDate|FHIRDateTime|FHIRTime|FHIRString|string|FHIRUri|FHIRAttachment|FHIRCoding|FHIRQuantity|FHIRReference|null valueX Single-valued answer to the question */
        public \FHIRBoolean|\FHIRDecimal|\FHIRInteger|\FHIRDate|\FHIRDateTime|\FHIRTime|\FHIRString|string|\FHIRUri|\FHIRAttachment|\FHIRCoding|\FHIRQuantity|\FHIRReference|null $valueX = null,
        /** @var array<FHIRQuestionnaireResponseItem> item Nested groups and questions */
        public array $item = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
