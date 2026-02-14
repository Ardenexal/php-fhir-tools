<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\EpisodeOfCareStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type EpisodeOfCareStatus
 *
 * @description Code type wrapper for EpisodeOfCareStatus enum
 */
class EpisodeOfCareStatusType extends CodePrimitive
{
    public function __construct(
        /** @param EpisodeOfCareStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}
