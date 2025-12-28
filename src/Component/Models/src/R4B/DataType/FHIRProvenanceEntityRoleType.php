<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRProvenanceEntityRole;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRProvenanceEntityRole
 *
 * @description Code type wrapper for FHIRProvenanceEntityRole enum
 */
class FHIRProvenanceEntityRoleType extends FHIRCode
{
    public function __construct(
        /** @var FHIRProvenanceEntityRole|string|null $value The code value */
        public FHIRProvenanceEntityRole|string|null $value = null,
    ) {
    }
}
