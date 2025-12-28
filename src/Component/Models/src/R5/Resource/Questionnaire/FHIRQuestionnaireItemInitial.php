<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description One or more values that should be pre-populated in the answer when initially rendering the questionnaire for user input.
 */
#[FHIRBackboneElement(parentResource: 'Questionnaire', elementPath: 'Questionnaire.item.initial', fhirVersion: 'R5')]
class FHIRQuestionnaireItemInitial extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|FHIRDecimal|FHIRInteger|FHIRDate|FHIRDateTime|FHIRTime|FHIRString|string|FHIRUri|FHIRAttachment|FHIRCoding|FHIRQuantity|FHIRReference|null valueX Actual value for initializing the question */
        #[NotBlank]
        public \FHIRBoolean|\FHIRDecimal|\FHIRInteger|\FHIRDate|\FHIRDateTime|\FHIRTime|\FHIRString|string|\FHIRUri|\FHIRAttachment|\FHIRCoding|\FHIRQuantity|\FHIRReference|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
