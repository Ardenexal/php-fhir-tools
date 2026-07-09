<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Extension;

/**
 * Reads FHIR Extension fields tolerantly, degrading to a neutral value instead of crashing on
 * partially-constructed objects.
 *
 * Deserializers instantiate model objects via `newInstanceWithoutConstructor()`, so any field the
 * payload did not carry is left as an **uninitialized typed property**. Reading such a property (or
 * calling a getter that returns it) throws `\Error` ("must not be accessed before initialization"),
 * not `null`. Every accessor here is guarded so an unreadable field is treated exactly like an
 * absent one — the same defer-not-deny discipline as `FHIRValidationService::readExtensionUrl()`,
 * generalised to `value[x]` and nested `extension[]` traversal.
 *
 * These guarded sub-extension reads are needed by the SDC extensions that carry child extensions —
 * `launchContext`, `observationLinkPeriod`, `definitionExtract`, `templateExtractContext`.
 */
final class SafeExtensionReader
{
    /**
     * Read an extension's canonical `url`, tolerating a constructor-bypassed object.
     *
     * @return string|null the URL, or null when absent, unreadable, or the object exposes no URL accessor
     */
    public function readUrl(object $ext): ?string
    {
        if (!method_exists($ext, 'getExtensionUrl')) {
            return null;
        }

        try {
            return $ext->getExtensionUrl();
        } catch (\Error) {
            // Uninitialized typed $url on a constructor-bypassed object -- rationale: an unreadable
            // URL must degrade to "absent", never crash the caller traversing extensions.
            return null;
        }
    }

    /**
     * Read an extension's `value[x]`, tolerating a constructor-bypassed object.
     *
     * @return mixed the value (a primitive wrapper or complex-type object per `value[x]`), or null
     *               when absent or unreadable
     */
    public function readValue(object $ext): mixed
    {
        if (!property_exists($ext, 'value')) {
            return null;
        }

        // `??` uses isset() semantics, which read an uninitialized typed property (the state a
        // constructor-bypassing deserializer leaves) as "absent" instead of throwing \Error.
        return $ext->value ?? null;
    }

    /**
     * Return an extension's child extensions (`ext.extension[]`), tolerating a constructor-bypassed object.
     *
     * @return list<object> the sub-extensions in declaration order, or an empty list when absent or unreadable
     */
    public function readSubExtensions(object $ext): array
    {
        if (!property_exists($ext, 'extension')) {
            return [];
        }

        // `??` reads an uninitialized typed property (constructor-bypassed object) as "absent"
        // via isset() semantics rather than throwing \Error.
        $subs = $ext->extension ?? [];

        if (!is_array($subs)) {
            return [];
        }

        return array_values(array_filter($subs, static fn (mixed $sub): bool => is_object($sub)));
    }

    /**
     * Find the first child extension of `$ext` whose URL matches `$url`.
     *
     * Guarded traversal of `ext.extension[]` — the sub-extension lookup complex SDC extensions rely
     * on (e.g. reading the `name`/`type` parts of `launchContext`).
     *
     * @return object|null the matching child extension, or null when none matches
     */
    public function findExtension(object $ext, string $url): ?object
    {
        foreach ($this->readSubExtensions($ext) as $child) {
            if ($this->readUrl($child) === $url) {
                return $child;
            }
        }

        return null;
    }
}
