<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Extension\SafeExtensionReader;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Exercises SafeExtensionReader over the two object shapes it must tolerate:
 *
 *  1. A **constructor-bypassed** Extension whose typed properties are uninitialized — the exact
 *     hazard deserializers create (`newInstanceWithoutConstructor()`), where reading a property
 *     throws `\Error` rather than returning null.
 *  2. A **deserialized fixture carrying sub-extensions** — reproduced here the same way the
 *     deserializer builds it (bypass the constructor, then assign the public properties directly).
 *     Assertions confirm the reader returns the *real* value and the *real* children, not merely
 *     that no `\Error` escaped.
 */
#[CoversClass(SafeExtensionReader::class)]
final class SafeExtensionReaderTest extends TestCase
{
    private SafeExtensionReader $reader;

    protected function setUp(): void
    {
        $this->reader = new SafeExtensionReader();
    }

    public function testReadsUrlValueAndSubExtensionsFromDeserializedFixture(): void
    {
        $nameValue = new StringPrimitive(value: 'patient');
        $nameChild = $this->deserialized(
            url: 'name',
            value: $nameValue,
        );
        $typeValue = new StringPrimitive(value: 'Patient');
        $typeChild = $this->deserialized(
            url: 'type',
            value: $typeValue,
        );

        $launchContext = $this->deserialized(
            url: 'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-launchContext',
            subExtensions: [$nameChild, $typeChild],
        );

        // URL is read from a populated object.
        self::assertSame(
            'http://hl7.org/fhir/uv/sdc/StructureDefinition/sdc-questionnaire-launchContext',
            $this->reader->readUrl($launchContext),
        );

        // Sub-extensions come back as the real child objects, in order.
        $subs = $this->reader->readSubExtensions($launchContext);
        self::assertCount(2, $subs);
        self::assertSame([$nameChild, $typeChild], $subs);

        // findExtension resolves a child by URL...
        self::assertSame($nameChild, $this->reader->findExtension($launchContext, 'name'));
        self::assertSame($typeChild, $this->reader->findExtension($launchContext, 'type'));

        // ...and readValue returns the real value object, not just "no Error thrown".
        self::assertSame($nameValue, $this->reader->readValue($nameChild));
        self::assertSame($typeValue, $this->reader->readValue($typeChild));
    }

    public function testFindExtensionReturnsNullWhenNoChildMatches(): void
    {
        $launchContext = $this->deserialized(
            url: 'urn:example',
            subExtensions: [$this->deserialized(url: 'name', value: new StringPrimitive(value: 'patient'))],
        );

        self::assertNull($this->reader->findExtension($launchContext, 'type'));
    }

    public function testDegradesGracefullyOnConstructorBypassedExtension(): void
    {
        // Every typed property is uninitialized: getExtensionUrl(), $value and $extension would each
        // throw \Error. The reader must degrade to null/[] on all of them without crashing.
        $bare = (new \ReflectionClass(Extension::class))->newInstanceWithoutConstructor();

        self::assertNull($this->reader->readUrl($bare));
        self::assertNull($this->reader->readValue($bare));
        self::assertSame([], $this->reader->readSubExtensions($bare));
        self::assertNull($this->reader->findExtension($bare, 'name'));
    }

    /**
     * Build an Extension the way the deserializer does: bypass the constructor, then assign the
     * public properties that were present in the payload (leaving the rest uninitialized).
     *
     * @param list<object> $subExtensions
     */
    private function deserialized(string $url, mixed $value = null, array $subExtensions = []): Extension
    {
        $ext      = (new \ReflectionClass(Extension::class))->newInstanceWithoutConstructor();
        $ext->url = $url;
        if ($value !== null) {
            $ext->value = $value;
        }
        if ($subExtensions !== []) {
            $ext->extension = $subExtensions;
        }

        return $ext;
    }
}
