<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRReportRelationshipType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRReportRelationshipType
 *
 * @description Code type wrapper for FHIRReportRelationshipType enum
 */
class FHIRReportRelationshipTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRReportRelationshipType|string|null $value The code value */
        public FHIRReportRelationshipType|string|null $value = null,
    ) {
    }
}
