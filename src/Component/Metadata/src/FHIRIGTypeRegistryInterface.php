<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata;

/**
 * Read side of the IG type registry: canonical URLs and raw payloads in, model class names out.
 *
 * Extracted so the registry can be decorated, cached, or replaced by a test double. Until this
 * existed the concrete class was the only thing callers could depend on, which meant nothing could
 * sit in front of it.
 *
 * Only the read methods belong here. Construction stays off the interface deliberately: the
 * registry takes plain arrays and hands back hydrated objects, because the compiled Symfony
 * container cannot serialize object instances when it dumps its definitions. Putting that
 * asymmetry on an interface would invite an implementation that accepts objects and breaks the
 * container build.
 *
 * @author Ardenexal
 */
interface FHIRIGTypeRegistryInterface
{
    /**
     * Resolve the extension class registered for a canonical extension URL.
     *
     * @param string $url     Canonical URL of the extension definition
     * @param string $version FHIR version to scope the lookup to; empty means any
     *
     * @return string|null Fully-qualified extension class, or null when the URL is not registered
     */
    public function resolveExtensionClass(string $url, string $version = ''): ?string;

    /**
     * Resolve the typed profile class registered for a canonical profile URL.
     *
     * @param string $profileUrl Canonical URL, as it appears in `meta.profile`
     *
     * @return string|null Fully-qualified profile class, or null when the URL is not registered
     */
    public function resolveProfileClass(string $profileUrl): ?string;

    /**
     * Resolve the typed slice class for a raw payload, using the base type's discriminators.
     *
     * @param string               $baseTypeFqcn Fully-qualified class of the unsliced base type
     * @param array<string, mixed> $data         Raw decoded payload to match discriminators against
     *
     * @return string|null Fully-qualified slice class, or null when no discriminator matches and the
     *                     caller should fall back to the base type
     */
    public function resolveSliceClass(string $baseTypeFqcn, array $data): ?string;

    /**
     * Discriminators registered against a base type, in the order they should be tried.
     *
     * @param string $baseTypeFqcn Fully-qualified class of the unsliced base type
     *
     * @return list<SliceDiscriminator> Hydrated discriminators; empty when the type has no slices
     */
    public function getSliceDiscriminators(string $baseTypeFqcn): array;

    /**
     * Every registered extension mapping, keyed by canonical URL then by FHIR version.
     *
     * Nested rather than flat because one extension URL may be published by several versions with
     * different generated classes.
     *
     * @return array<string, array<string, class-string>> Extension URL to version to class
     */
    public function getExtensionMappings(): array;

    /**
     * Every registered profile mapping.
     *
     * @return array<string, string> Canonical profile URL to fully-qualified class
     */
    public function getProfileMappings(): array;

    /**
     * Every registered slice-discriminator mapping, hydrated.
     *
     * Note the asymmetry: the constructor takes plain arrays because the compiled container cannot
     * dump object instances, but this getter hands back hydrated objects. Callers get objects on the
     * way out regardless of what went in.
     *
     * @return array<string, list<SliceDiscriminator>> Base type class to its hydrated discriminators
     */
    public function getSliceDiscriminatorMappings(): array;
}
