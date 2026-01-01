<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIREligibilityOutcome;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREligibilityOutcome
 *
 * @description Code type wrapper for FHIREligibilityOutcome enum
 */
class FHIREligibilityOutcomeType extends FHIRCode
{
    public function __construct(
        /** @param FHIREligibilityOutcome|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
