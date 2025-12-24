<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRProvenanceEntityRole;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRProvenanceEntityRole
 *
 * @description Code type wrapper for FHIRProvenanceEntityRole enum
 */
class FHIRFHIRProvenanceEntityRoleType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRProvenanceEntityRole|string|null $value The code value */
        public FHIRFHIRProvenanceEntityRole|string|null $value = null,
    ) {
    }
}
