<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ReportRelationshipType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ReportRelationshipType
 *
 * @description Code type wrapper for ReportRelationshipType enum
 */
class ReportRelationshipTypeType extends CodePrimitive
{
    public function __construct(
        /** @param ReportRelationshipType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
