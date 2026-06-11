<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRQuestionnaireResolver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InMemoryFHIRQuestionnaireResolver::class)]
final class InMemoryFHIRQuestionnaireResolverTest extends TestCase
{
    public function testResolvesQuestionnaireByExactUrl(): void
    {
        $q        = new QuestionnaireResource(url: new UriPrimitive(value: 'http://example.org/q1'));
        $resolver = new InMemoryFHIRQuestionnaireResolver([$q]);

        self::assertSame($q, $resolver->resolve('http://example.org/q1'));
    }

    public function testReturnsNullForUnknownUrl(): void
    {
        $q        = new QuestionnaireResource(url: new UriPrimitive(value: 'http://example.org/q1'));
        $resolver = new InMemoryFHIRQuestionnaireResolver([$q]);

        self::assertNull($resolver->resolve('http://example.org/unknown'));
    }

    public function testReturnsCorrectQuestionnaireFromMultiple(): void
    {
        $q1 = new QuestionnaireResource(url: new UriPrimitive(value: 'http://example.org/q1'));
        $q2 = new QuestionnaireResource(url: new UriPrimitive(value: 'http://example.org/q2'));
        $q3 = new QuestionnaireResource(url: new UriPrimitive(value: 'http://example.org/q3'));

        $resolver = new InMemoryFHIRQuestionnaireResolver([$q1, $q2, $q3]);

        self::assertSame($q2, $resolver->resolve('http://example.org/q2'));
        self::assertSame($q3, $resolver->resolve('http://example.org/q3'));
        self::assertNull($resolver->resolve('http://example.org/q4'));
    }

    public function testSkipsQuestionnairesWithoutUrl(): void
    {
        $noUrl    = new QuestionnaireResource();
        $resolver = new InMemoryFHIRQuestionnaireResolver([$noUrl]);

        self::assertNull($resolver->resolve(''));
        self::assertNull($resolver->resolve('http://example.org/q1'));
    }

    public function testExactMatchOnlyNoVersionSuffix(): void
    {
        $q        = new QuestionnaireResource(url: new UriPrimitive(value: 'http://example.org/q1'));
        $resolver = new InMemoryFHIRQuestionnaireResolver([$q]);

        // Version suffix is not stripped — exact match only
        self::assertNull($resolver->resolve('http://example.org/q1|1.0.0'));
    }
}
