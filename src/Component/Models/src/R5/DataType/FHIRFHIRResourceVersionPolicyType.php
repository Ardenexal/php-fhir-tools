<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRResourceVersionPolicy;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRResourceVersionPolicy
 *
 * @description Code type wrapper for FHIRResourceVersionPolicy enum
 */
class FHIRFHIRResourceVersionPolicyType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRResourceVersionPolicy|string|null $value The code value */
        public FHIRFHIRResourceVersionPolicy|string|null $value = null,
    ) {
    }
}
