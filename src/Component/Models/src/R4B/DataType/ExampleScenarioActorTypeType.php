<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ExampleScenarioActorType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ExampleScenarioActorType
 *
 * @description Code type wrapper for ExampleScenarioActorType enum
 */
class ExampleScenarioActorTypeType extends CodePrimitive
{
    public function __construct(
        /** @param ExampleScenarioActorType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
