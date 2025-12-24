<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRExampleScenarioActorType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRExampleScenarioActorType
 *
 * @description Code type wrapper for FHIRExampleScenarioActorType enum
 */
class FHIRFHIRExampleScenarioActorTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRExampleScenarioActorType|string|null $value The code value */
        public FHIRFHIRExampleScenarioActorType|string|null $value = null,
    ) {
    }
}
