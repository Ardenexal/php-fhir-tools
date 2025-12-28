<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Expressions that describe applicability criteria for the billing code.
 */
#[FHIRBackboneElement(parentResource: 'ChargeItemDefinition', elementPath: 'ChargeItemDefinition.applicability', fhirVersion: 'R4B')]
class FHIRChargeItemDefinitionApplicability extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Natural language description of the condition */
        public FHIRString|string|null $description = null,
        /** @var FHIRString|string|null language Language of the expression */
        public FHIRString|string|null $language = null,
        /** @var FHIRString|string|null expression Boolean-valued expression */
        public FHIRString|string|null $expression = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
