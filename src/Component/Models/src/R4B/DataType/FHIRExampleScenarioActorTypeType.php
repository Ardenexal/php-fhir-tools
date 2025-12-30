<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRExampleScenarioActorType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRExampleScenarioActorType
 *
 * @description Code type wrapper for FHIRExampleScenarioActorType enum
 */
class FHIRExampleScenarioActorTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRExampleScenarioActorType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
