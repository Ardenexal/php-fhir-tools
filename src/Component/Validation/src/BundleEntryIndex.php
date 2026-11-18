<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * One Bundle's entries, read once, and the rules that resolve a reference to them.
 *
 * {@see BundleReferenceResolutionChecker} and {@see BundleReachabilityChecker} both ask "which entries
 * does this reference name?", and both must answer it the way the HL7 Java reference validator does.
 * This class is that answer, so the two rules cannot drift apart. What a given number of matches
 * *means* stays with each checker: two matches is a finding for resolution, and simply no link for
 * reachability.
 *
 * ## Resolution rules
 *
 * - **Absolute** (the value has a scheme, `http:` or `urn:`): the entries whose `fullUrl` is that
 *   value, once any `#fragment` is dropped. With none, a `/_history/n` suffix is stripped and the
 *   entries are narrowed to the one whose `meta.versionId` is `n`.
 * - **Relative** (`[Type]/[id]`, optionally `/_history/n`): the referring entry's `fullUrl` must read
 *   `[base]/[type]/[id]` with `[id]` equal to that resource's own id, and the target is `[base]` plus
 *   the reference. The base may be empty, so `Patient/1` under the fullUrl `Composition/1` resolves to
 *   an entry whose fullUrl is `Patient/1`. A `_history/n` suffix narrows the matches by `meta.versionId`:
 *   `bundle-document-versioned-references-good` holds `_history/1` and `_history/2` against two entries
 *   sharing one fullUrl, and the reference validator accepts both, while its `-bad` twin's
 *   `_history/3` matches nothing.
 *
 * The source's id decides, never the target's: `relative_reference_to_fullUrl.id_in_target_resource`
 * holds the target with an id and a Composition without one, and the reference validator does not
 * resolve it. Under a `urn:uuid:` fullUrl nothing relative resolves at all.
 *
 * Built by {@see BundleEntryReader::index()}, and held only for one validation, because model
 * properties are mutable.
 *
 * @internal shared by the Bundle checkers of this component, not part of the public API
 *
 * @phpstan-type IndexedEntry array{entry: object, fullUrl: string|null, hasFullUrl: bool, resource: object|null, type: string|null, id: string|null, version: string|null}
 */
final class BundleEntryIndex
{
    /** A value with a URI scheme resolves by `fullUrl` alone. */
    private const string ABSOLUTE = '~\A[A-Za-z][A-Za-z0-9+.\-]*:~';

    /**
     * A relative reference, `[Type]/[id]`, optionally pinned to a version.
     *
     * Both the id and the version must have the shape of a FHIR id. A reference with any other version,
     * such as `Patient/1/_history/v_1`, is not one this class resolves.
     */
    private const string RELATIVE_REFERENCE = '~\A(?<type>[A-Z][A-Za-z]+)/(?<id>[A-Za-z0-9\-\.]{1,64})(?:/_history/(?<version>[A-Za-z0-9\-\.]{1,64}))?\z~';

    /** A version suffix on an absolute reference. */
    private const string HISTORY_SUFFIX = '~\A(?<url>.+)/_history/(?<version>[^/]+)\z~';

    /**
     * A `fullUrl` that yields a base: `[base]/[type]/[id]`, where `[id]` must be the entry's own.
     *
     * The base is allowed to be **empty**. `mni-patientOverview-bundle-example1` identifies its entries
     * relatively, as `Composition/1` and `Patient/1` (which {@see BundleEntryFullUrlChecker} reports
     * separately), and against an empty base `Patient/1` resolves to
     * exactly what that bundle holds; the reference validator reports nothing there. Its twin
     * `…example1b` differs in one character, a Composition whose id is `1a` against a fullUrl saying
     * `1`, and the reference validator reports both of its references. A required trailing slash
     * collapses that distinction and reports both bundles.
     */
    private const string RESTFUL_FULL_URL = '~\A(?<base>(?:.*/)?)(?<type>[A-Za-z]+)/(?<id>[A-Za-z0-9\-\.]{1,64})\z~';

    /** @var array<string, list<int>> entry positions by `fullUrl` */
    private readonly array $byFullUrl;

    /**
     * @param array<int, IndexedEntry> $entries every entry by its position in `Bundle.entry`, with or without a resource
     */
    public function __construct(public readonly array $entries)
    {
        $byFullUrl = [];

        foreach ($entries as $index => $entry) {
            if ($entry['fullUrl'] !== null) {
                $byFullUrl[$entry['fullUrl']][] = $index;
            }
        }

        $this->byFullUrl = $byFullUrl;
    }

    /**
     * Split a relative reference into its type, id and version, or null when it is not one.
     *
     * @return array{type: string, id: string, version: string|null}|null
     */
    public static function parseRelative(string $reference): ?array
    {
        if (preg_match(self::RELATIVE_REFERENCE, $reference, $parts) !== 1) {
            return null;
        }

        return [
            'type'    => $parts['type'],
            'id'      => $parts['id'],
            'version' => ($parts['version'] ?? '') === '' ? null : $parts['version'],
        ];
    }

    /** Whether a value carries a URI scheme, and so resolves by `fullUrl` alone. */
    public static function isAbsolute(string $value): bool
    {
        return preg_match(self::ABSOLUTE, $value) === 1;
    }

    /**
     * The entries a relative reference inside the entry at `$source` resolves to.
     *
     * Empty when the source entry yields no base, as well as when nothing matches.
     *
     * @return list<int>
     */
    public function resolveRelative(string $type, string $id, ?string $version, int $source): array
    {
        $base = $this->base($source);

        if ($base === null) {
            return [];
        }

        return $this->withVersion($this->byFullUrl[$base . $type . '/' . $id] ?? [], $version);
    }

    /**
     * The entries an absolute value resolves to.
     *
     * @return list<int>
     */
    public function resolveAbsolute(string $url): array
    {
        // `urn:uuid:…#section` points into the entry `urn:uuid:…`.
        $url = explode('#', $url, 2)[0];

        if (isset($this->byFullUrl[$url])) {
            return $this->byFullUrl[$url];
        }

        if (preg_match(self::HISTORY_SUFFIX, $url, $parts) === 1) {
            return $this->withVersion($this->byFullUrl[$parts['url']] ?? [], $parts['version']);
        }

        return [];
    }

    /**
     * The fullUrls of the entries holding a resource of this type and id, whatever their fullUrl says.
     *
     * @return list<string>
     */
    public function fullUrlsOfTypeAndId(string $type, string $id): array
    {
        $fullUrls = [];

        foreach ($this->entries as $entry) {
            if ($entry['type'] === $type && $entry['id'] === $id && $entry['fullUrl'] !== null) {
                $fullUrls[] = $entry['fullUrl'];
            }
        }

        return $fullUrls;
    }

    /** The base a relative reference inside the entry at `$source` resolves against, or null for none. */
    private function base(int $source): ?string
    {
        $entry   = $this->entries[$source] ?? null;
        $fullUrl = $entry['fullUrl']       ?? null;

        if ($entry === null || $fullUrl === null || preg_match(self::RESTFUL_FULL_URL, $fullUrl, $parts) !== 1) {
            return null;
        }

        // The tail is only a `[type]/[id]` to strip when the id is genuinely this resource's own.
        if ($entry['id'] === null || $parts['id'] !== $entry['id']) {
            return null;
        }

        return $parts['base'];
    }

    /**
     * Narrow candidates to the version a `_history/n` reference names.
     *
     * @param list<int> $candidates
     *
     * @return list<int>
     */
    private function withVersion(array $candidates, ?string $version): array
    {
        if ($version === null) {
            return $candidates;
        }

        return array_values(array_filter(
            $candidates,
            fn (int $index): bool => $this->entries[$index]['version'] === $version,
        ));
    }
}
