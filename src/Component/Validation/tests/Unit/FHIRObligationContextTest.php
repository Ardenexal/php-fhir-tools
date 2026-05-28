<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRObligation;
use Ardenexal\FHIRTools\Component\Validation\FHIRObligationContext;
use PHPUnit\Framework\TestCase;

final class FHIRObligationContextTest extends TestCase
{
    private const string PLACER = 'http://example.org/actor/placer';

    private const string FILLER = 'http://example.org/actor/filler';

    public function testNullActorObligationMatchesAnyContext(): void
    {
        $ctx        = new FHIRObligationContext(self::PLACER);
        $obligation = new FHIRObligation(code: 'SHALL:populate', actor: null);

        self::assertTrue($ctx->matchesObligation($obligation));
    }

    public function testNullActorObligationMatchesNullContextActor(): void
    {
        $ctx        = new FHIRObligationContext(null);
        $obligation = new FHIRObligation(code: 'SHALL:populate', actor: null);

        self::assertTrue($ctx->matchesObligation($obligation));
    }

    public function testMatchingActorObligationMatchesContext(): void
    {
        $ctx        = new FHIRObligationContext(self::PLACER);
        $obligation = new FHIRObligation(code: 'SHALL:populate', actor: self::PLACER);

        self::assertTrue($ctx->matchesObligation($obligation));
    }

    public function testDifferentActorObligationDoesNotMatchContext(): void
    {
        $ctx        = new FHIRObligationContext(self::PLACER);
        $obligation = new FHIRObligation(code: 'SHALL:populate', actor: self::FILLER);

        self::assertFalse($ctx->matchesObligation($obligation));
    }

    public function testSpecificActorObligationDoesNotMatchNullContextActor(): void
    {
        $ctx        = new FHIRObligationContext(null);
        $obligation = new FHIRObligation(code: 'SHALL:populate', actor: self::PLACER);

        self::assertFalse($ctx->matchesObligation($obligation));
    }
}
