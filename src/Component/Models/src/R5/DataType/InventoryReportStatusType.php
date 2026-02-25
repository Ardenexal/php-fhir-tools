<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\InventoryReportStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type InventoryReportStatus
 *
 * @description Code type wrapper for InventoryReportStatus enum
 */
class InventoryReportStatusType extends CodePrimitive
{
    public function __construct(
        /** @param InventoryReportStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
