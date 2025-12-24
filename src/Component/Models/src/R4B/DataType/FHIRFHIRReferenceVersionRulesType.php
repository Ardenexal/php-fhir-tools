<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRReferenceVersionRules;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRReferenceVersionRules
 *
 * @description Code type wrapper for FHIRReferenceVersionRules enum
 */
class FHIRFHIRReferenceVersionRulesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRReferenceVersionRules|string|null $value The code value */
        public FHIRFHIRReferenceVersionRules|string|null $value = null,
    ) {
    }
}
