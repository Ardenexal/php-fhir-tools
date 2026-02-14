<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ChargeItemDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Expressions that describe applicability criteria for the billing code.
 */
#[FHIRBackboneElement(parentResource: 'ChargeItemDefinition', elementPath: 'ChargeItemDefinition.applicability', fhirVersion: 'R4')]
class ChargeItemDefinitionApplicability extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null description Natural language description of the condition */
        public StringPrimitive|string|null $description = null,
        /** @var StringPrimitive|string|null language Language of the expression */
        public StringPrimitive|string|null $language = null,
        /** @var StringPrimitive|string|null expression Boolean-valued expression */
        public StringPrimitive|string|null $expression = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
