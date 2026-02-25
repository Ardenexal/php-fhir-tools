<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\RelatedArtifactTypeExpanded;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type RelatedArtifactTypeExpanded
 *
 * @description Code type wrapper for RelatedArtifactTypeExpanded enum
 */
class RelatedArtifactTypeExpandedType extends CodePrimitive
{
    public function __construct(
        /** @param RelatedArtifactTypeExpanded|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
