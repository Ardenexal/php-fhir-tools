<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @var FHIRReferenceVersionRules|string|null $value The code value */
        public FHIRReferenceVersionRules|string|null $value = null,
    ) {
    }
}
