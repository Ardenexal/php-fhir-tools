<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * Extension whose ONLY context is type=extension: it is permitted solely when nested
 * inside an extension carrying the declared parent URL. A single context keeps the
 * classification verdict observable at the whole-validator level (no sibling context
 * can OR-permit or defer past it — see footgun extension-context-or-semantics).
 */
#[FHIRExtensionContext(type: 'extension', expression: ExtensionContextOnlyExtensionFixture::DECLARED_PARENT_URL)]
final class ExtensionContextOnlyExtensionFixture implements FHIRExtensionInterface
{
    public const DECLARED_PARENT_URL = 'http://example.org/ext/declared-parent';

    public function getExtensionUrl(): ?string
    {
        return 'http://example.org/ext/extension-context-only';
    }
}
