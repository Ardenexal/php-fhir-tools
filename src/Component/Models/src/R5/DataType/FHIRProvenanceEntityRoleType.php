<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

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
        /** @param FHIRProvenanceEntityRole|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
