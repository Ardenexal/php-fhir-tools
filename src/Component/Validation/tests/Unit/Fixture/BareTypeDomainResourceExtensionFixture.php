<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

#[FHIRExtensionContext(type: 'element', expression: 'DomainResource')]
final class BareTypeDomainResourceExtensionFixture implements FHIRExtensionInterface
{
    public function getExtensionUrl(): ?string
    {
        return 'http://example.org/ext/bare-type-domain-resource';
    }
}
