<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRContributorType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRContributorType
 *
 * @description Code type wrapper for FHIRContributorType enum
 */
class FHIRFHIRContributorTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRContributorType|string|null $value The code value */
        public FHIRFHIRContributorType|string|null $value = null,
    ) {
    }
}
