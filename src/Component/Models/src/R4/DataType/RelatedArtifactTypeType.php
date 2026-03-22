<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\RelatedArtifactType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type RelatedArtifactType
 *
 * @description Code type wrapper for RelatedArtifactType enum
 */
class RelatedArtifactTypeType extends CodePrimitive
{
    public function __construct(
        /** @param RelatedArtifactType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
