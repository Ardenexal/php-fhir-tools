<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * M01 runtime proof: deserializers build resources via newInstanceWithoutConstructor(), so a
 * resource whose JSON carries no `extension` key leaves the promoted, non-nullable `$extension`
 * property *uninitialized*. The FHIRExtensionsTrait accessors must return [] (via catch(\Error))
 * rather than throw "must not be accessed before initialization". PHPStan cannot model
 * newInstanceWithoutConstructor, so this guarantee is only provable at runtime.
 */
final class ExtensionlessResourceGetExtensionsTest extends TestCase
{
    public function testGetExtensionsOnDeserialisedExtensionlessResourceReturnsEmptyArray(): void
    {
        $service = FHIRSerializationService::createDefault();

        // Minimal Patient with NO `extension` key → $extension is left uninitialized.
        $patient = $service->deserialize('{"resourceType":"Patient","id":"example"}');

        // Invoke via reflection: getExtensions() is provided only by the trait (no interface).
        $getExtensions = new \ReflectionMethod($patient, 'getExtensions');
        $result        = $getExtensions->invoke($patient);

        self::assertSame([], $result);
    }
}
