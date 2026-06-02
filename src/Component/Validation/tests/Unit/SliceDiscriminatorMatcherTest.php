<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class SliceDiscriminatorMatcherTest extends TestCase
{
    private SliceDiscriminatorMatcher $matcher;

    protected function setUp(): void
    {
        $this->matcher = new SliceDiscriminatorMatcher(PropertyAccess::createPropertyAccessor());
    }

    // ------------------------------------------------------------------
    // value discriminator
    // ------------------------------------------------------------------

    public function testValueMatchesExactString(): void
    {
        $item         = new \stdClass();
        $item->system = 'http://example.org';

        self::assertTrue($this->matcher->matches($item, 'value', 'system', 'http://example.org'));
    }

    public function testValueReturnsFalseForDifferentString(): void
    {
        $item         = new \stdClass();
        $item->system = 'http://other.org';

        self::assertFalse($this->matcher->matches($item, 'value', 'system', 'http://example.org'));
    }

    public function testValueReturnsFalseForNullAtPath(): void
    {
        $item         = new \stdClass();
        $item->system = null;

        self::assertFalse($this->matcher->matches($item, 'value', 'system', 'http://example.org'));
    }

    public function testValueMatchesStringableObject(): void
    {
        $primitive = new class ('http://example.org') implements \Stringable {
            public function __construct(private readonly string $value)
            {
            }

            public function __toString(): string
            {
                return $this->value;
            }
        };

        $item         = new \stdClass();
        $item->system = $primitive;

        self::assertTrue($this->matcher->matches($item, 'value', 'system', 'http://example.org'));
    }

    public function testValueMatchesNestedDotPath(): void
    {
        $item               = new \stdClass();
        $item->type         = new \stdClass();
        $item->type->system = 'http://example.org';

        self::assertTrue($this->matcher->matches($item, 'value', 'type.system', 'http://example.org'));
    }

    // ------------------------------------------------------------------
    // pattern discriminator
    // ------------------------------------------------------------------

    public function testPatternMatchesSubsetAtPath(): void
    {
        $coding = new class () implements \JsonSerializable {
            public function jsonSerialize(): mixed
            {
                return ['system' => 'http://example.org', 'code' => 'abc'];
            }
        };

        $item         = new \stdClass();
        $item->coding = $coding;

        self::assertTrue(
            $this->matcher->matches($item, 'pattern', 'coding', ['system' => 'http://example.org']),
        );
    }

    public function testPatternReturnsFalseForMissingPatternKey(): void
    {
        $coding = new class () implements \JsonSerializable {
            public function jsonSerialize(): mixed
            {
                return ['code' => 'abc'];
            }
        };

        $item         = new \stdClass();
        $item->coding = $coding;

        self::assertFalse(
            $this->matcher->matches($item, 'pattern', 'coding', ['system' => 'http://example.org']),
        );
    }

    public function testPatternWithScalarFallsBackToValueMatch(): void
    {
        $item         = new \stdClass();
        $item->system = 'http://example.org';

        self::assertTrue(
            $this->matcher->matches($item, 'pattern', 'system', 'http://example.org'),
        );
    }

    // ------------------------------------------------------------------
    // exists discriminator
    // ------------------------------------------------------------------

    public function testExistsTrueMatchesWhenPathPresent(): void
    {
        $item         = new \stdClass();
        $item->system = 'http://example.org';

        self::assertTrue($this->matcher->matches($item, 'exists', 'system', true));
    }

    public function testExistsFalseMatchesWhenPathAbsent(): void
    {
        $item = new \stdClass();

        self::assertTrue($this->matcher->matches($item, 'exists', 'missingField', false));
    }

    public function testExistsTrueReturnsFalseWhenPathAbsent(): void
    {
        $item = new \stdClass();

        self::assertFalse($this->matcher->matches($item, 'exists', 'missingField', true));
    }

    public function testExistsFalseReturnsFalseWhenPathPresent(): void
    {
        $item         = new \stdClass();
        $item->system = 'http://example.org';

        self::assertFalse($this->matcher->matches($item, 'exists', 'system', false));
    }

    // ------------------------------------------------------------------
    // unsupported discriminator types
    // ------------------------------------------------------------------

    public function testUnsupportedTypeEmitsWarningAndReturnsFalse(): void
    {
        $item    = new \stdClass();
        $warning = null;

        set_error_handler(static function(int $_errno, string $errstr) use (&$warning): bool {
            $warning = $errstr;

            return true;
        });

        $result = $this->matcher->matches($item, 'type', 'path', 'value');

        restore_error_handler();

        self::assertFalse($result);
        self::assertStringContainsString('not supported', $warning ?? '');
    }

    public function testUnsupportedProfileTypeEmitsWarningAndReturnsFalse(): void
    {
        $item    = new \stdClass();
        $warning = null;

        set_error_handler(static function(int $_errno, string $errstr) use (&$warning): bool {
            $warning = $errstr;

            return true;
        });

        $result = $this->matcher->matches($item, 'profile', 'path', 'value');

        restore_error_handler();

        self::assertFalse($result);
        self::assertStringContainsString('not supported', $warning ?? '');
    }
}
