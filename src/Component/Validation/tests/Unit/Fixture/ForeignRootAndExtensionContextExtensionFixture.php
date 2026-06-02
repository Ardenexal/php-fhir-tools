<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * BestPracticeExtension-shaped fixture: a foreign-root element context PLUS a
 * type=extension context. Inside a non-matching tree the element context defers,
 * and OR aggregation (anyDeferred) masks the extension arm's DENY — so this fixture
 * must never produce a whole-validator violation (footgun extension-context-or-semantics).
 */
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition.constraint')]
#[FHIRExtensionContext(type: 'extension', expression: ExtensionContextOnlyExtensionFixture::DECLARED_PARENT_URL)]
final class ForeignRootAndExtensionContextExtensionFixture implements FHIRExtensionInterface
{
    public function getExtensionUrl(): ?string
    {
        return 'http://example.org/ext/foreign-root-and-extension-context';
    }
}
