<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Traits\FHIRExtensionsTrait;

/**
 * Context-free parent extension carrying nested sub-extensions, with a configurable URL
 * so tests can build matching, non-matching, and unknown (null URL) ancestor chains.
 */
final class NestableParentExtensionFixture implements FHIRExtensionInterface
{
    use FHIRExtensionsTrait;

    /** @param list<FHIRExtensionInterface> $extension */
    public function __construct(
        private readonly ?string $url,
        private readonly array $extension = [],
    ) {
    }

    public function getExtensionUrl(): ?string
    {
        return $this->url;
    }
}
