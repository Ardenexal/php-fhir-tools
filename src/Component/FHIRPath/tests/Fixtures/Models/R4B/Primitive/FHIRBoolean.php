<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive;

use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\DataType\FHIRElement;

/**
 * Mock FHIRBoolean primitive for testing.
 */
class FHIRBoolean extends FHIRElement
{
    public function __construct(
        ?string $id = null,
        array $extension = [],
        public ?bool $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}
