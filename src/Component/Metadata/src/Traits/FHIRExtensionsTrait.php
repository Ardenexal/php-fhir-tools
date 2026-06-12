<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Traits;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * Provides extension-finding helpers for FHIR types that carry an $extension array.
 *
 * Applied by the generator to the Element and DomainResource base classes for each
 * FHIR version so it is available on all data types, complex types, backbone elements,
 * and domain resources without being listed explicitly on every generated class.
 *
 * Two complementary lookup strategies are provided:
 *
 *  - **By typed class** (`findExtension`, `findExtensions`, `hasExtension`) — uses
 *    `instanceof` to match typed IG extension subclasses. Works when the IG type registry
 *    is configured so that deserialization produces typed objects.
 *
 *  - **By URL** (`findExtensionByUrl`, `findExtensionsByUrl`) — matches the raw URL
 *    string on any FHIRExtensionInterface object. Works regardless of whether the
 *    registry is configured and is the equivalent of the FHIRPath expression
 *    `extension.where(url = 'http://...')`.
 *
 * @author Ardenexal
 */
trait FHIRExtensionsTrait
{
    /**
     * Return the first extension that is an instance of the given typed extension class.
     *
     * Returns null when no match is found or when the IG type registry is not configured
     * and the extension array contains only base Extension objects.
     *
     * @template T of object
     *
     * @param class-string<T> $extensionClass Fully-qualified name of the typed extension class
     *
     * @return T|null
     */
    public function findExtension(string $extensionClass): ?object
    {
        foreach ($this->extensionList() as $ext) {
            if ($ext instanceof $extensionClass) {
                return $ext;
            }
        }

        return null;
    }

    /**
     * Return all extensions that are instances of the given typed extension class.
     *
     * @template T of object
     *
     * @param class-string<T> $extensionClass Fully-qualified name of the typed extension class
     *
     * @return list<T>
     */
    public function findExtensions(string $extensionClass): array
    {
        $found = [];
        foreach ($this->extensionList() as $ext) {
            if ($ext instanceof $extensionClass) {
                $found[] = $ext;
            }
        }

        return $found;
    }

    /**
     * Return true when at least one extension is an instance of the given typed class.
     *
     * @param class-string $extensionClass Fully-qualified name of the typed extension class
     */
    public function hasExtension(string $extensionClass): bool
    {
        foreach ($this->extensionList() as $ext) {
            if ($ext instanceof $extensionClass) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return the first extension whose URL equals the given string.
     *
     * Works with both typed IG extension objects and plain base Extension objects,
     * making it useful when the IG type registry is not configured. This is the
     * equivalent of the FHIRPath expression `extension.where(url = 'http://...')`.
     *
     * Returns null when no extension with that URL is present.
     */
    public function findExtensionByUrl(string $url): ?FHIRExtensionInterface
    {
        foreach ($this->extensionList() as $ext) {
            if ($ext->getExtensionUrl() === $url) {
                return $ext;
            }
        }

        return null;
    }

    /**
     * Return all extensions whose URL equals the given string.
     *
     * @return list<FHIRExtensionInterface>
     */
    public function findExtensionsByUrl(string $url): array
    {
        $found = [];
        foreach ($this->extensionList() as $ext) {
            if ($ext->getExtensionUrl() === $url) {
                $found[] = $ext;
            }
        }

        return $found;
    }

    /**
     * Return all extensions attached to this element.
     *
     * @return list<FHIRExtensionInterface>
     */
    public function getExtensions(): array
    {
        return $this->extensionList();
    }

    /**
     * Return this element's extensions as a safe list.
     *
     * The generated `$extension` property is a non-nullable typed array, but the
     * deserializer instantiates objects via `newInstanceWithoutConstructor()` and only
     * assigns properties present in the payload — so on a resource that carries no
     * `extension` key the property is left *uninitialized*. Reading it directly then
     * throws `Error: Typed property ... must not be accessed before initialization`.
     * This mirrors the constructor-bypass guard used by `FHIRValidationService::readExtensionUrl()`.
     *
     * @return list<FHIRExtensionInterface>
     */
    private function extensionList(): array
    {
        try {
            return array_values($this->extension);
        } catch (\Error) {
            return [];
        }
    }
}
