<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRReferenceVersionRules;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRReferenceVersionRules
 *
 * @description Code type wrapper for FHIRReferenceVersionRules enum
 */
class FHIRReferenceVersionRulesType extends FHIRCode
{
    public function __construct(
        /** @param FHIRReferenceVersionRules|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
