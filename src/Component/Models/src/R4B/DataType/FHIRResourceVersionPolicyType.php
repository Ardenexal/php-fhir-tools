<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRResourceVersionPolicy;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRResourceVersionPolicy
 *
 * @description Code type wrapper for FHIRResourceVersionPolicy enum
 */
class FHIRResourceVersionPolicyType extends FHIRCode
{
    public function __construct(
        /** @var FHIRResourceVersionPolicy|string|null $value The code value */
        public FHIRResourceVersionPolicy|string|null $value = null,
    ) {
    }
}
