<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description Expressions that describe applicability criteria for the billing code.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ChargeItemDefinition', elementPath: 'ChargeItemDefinition.applicability', fhirVersion: 'R4')]
class FHIRChargeItemDefinitionApplicability extends FHIRBackboneElement
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
