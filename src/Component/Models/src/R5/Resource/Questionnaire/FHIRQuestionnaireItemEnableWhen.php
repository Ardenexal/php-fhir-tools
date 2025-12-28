<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRTime;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A constraint indicating that this item should only be enabled (displayed/allow answers to be captured) when the specified condition is true.
 */
#[FHIRBackboneElement(parentResource: 'Questionnaire', elementPath: 'Questionnaire.item.enableWhen', fhirVersion: 'R5')]
class FHIRQuestionnaireItemEnableWhen extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null question The linkId of question that determines whether item is enabled/disabled */
        #[NotBlank]
        public FHIRString|string|null $question = null,
        /** @var FHIRQuestionnaireItemOperatorType|null operator exists | = | != | > | < | >= | <= */
        #[NotBlank]
        public ?FHIRQuestionnaireItemOperatorType $operator = null,
        /** @var FHIRBoolean|FHIRDecimal|FHIRInteger|FHIRDate|FHIRDateTime|FHIRTime|FHIRString|string|FHIRCoding|FHIRQuantity|FHIRReference|null answerX Value for question comparison based on operator */
        #[NotBlank]
        public FHIRBoolean|FHIRDecimal|FHIRInteger|FHIRDate|FHIRDateTime|FHIRTime|FHIRString|string|FHIRCoding|FHIRQuantity|FHIRReference|null $answerX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
