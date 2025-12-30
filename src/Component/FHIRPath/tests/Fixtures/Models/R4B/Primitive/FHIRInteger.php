<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\Primitive;

use Ardenexal\FHIRTools\Component\FHIRPath\Tests\Fixtures\Models\R4B\DataType\FHIRElement;

/**
 * Mock FHIRInteger primitive for testing.
 */
class FHIRInteger extends FHIRElement
{
    public function __construct(
        ?string $id = null,
        array $extension = [],
        public ?int $value = null,
    ) {
        parent::__construct($id, $extension);
    }
}
