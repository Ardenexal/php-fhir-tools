<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Psr\Cache\CacheItemPoolInterface;

/**
 * Decorator that caches terminology validation results to avoid repeated server calls.
 *
 * Results are stored in an in-process array for the lifetime of the request. When a
 * PSR-6 pool is provided, results are also persisted across requests with the configured TTL.
 *
 * Only a verdict is cached. When the inner client implements {@see FHIRTerminologyMembershipClientInterface}
 * and gives no answer (a refused request, a timeout), nothing is stored, so a passing outage is not
 * remembered as "not a member" for the length of the TTL.
 */
final class CachingFHIRTerminologyClient implements FHIRTerminologyMembershipClientInterface
{
    /**
     * Prefix of the membership cache keys.
     *
     * Before #182 a refused or failed request was stored as `false`, a "not a member" verdict, and a
     * bare code was asked without `inferSystem`, which Ontoserver and tx.fhir.org always refuse. A
     * PSR-6 pool kept from then can still hold those entries, so membership keys carry this prefix and
     * never read them. Bump it whenever stored membership verdicts stop meaning what they meant.
     */
    private const string MEMBERSHIP_KEY_VERSION = 'v2::';

    /** @var array<string, bool|CodingValidationResult> */
    private array $inProcess = [];

    public function __construct(
        private readonly FHIRTerminologyClientInterface $inner,
        private readonly ?CacheItemPoolInterface $cache = null,
        private readonly int $ttl = 3600,
    ) {
    }

    /**
     * Returns true when $value is a valid member of the named value set, caching the result.
     *
     * Non-cacheable values (null, objects other than BackedEnum) bypass the cache and delegate
     * directly to the inner client.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param mixed  $value       The code to validate; accepts string, int, or BackedEnum
     *
     * @return bool True when the code is a valid member, false otherwise
     */
    public function validateCode(string $valueSetUrl, mixed $value): bool
    {
        return $this->checkCode($valueSetUrl, $value) === true;
    }

    /**
     * Whether a code is in the value set, or null when the inner client gave no answer; verdicts are cached.
     *
     * An inner client without {@see FHIRTerminologyMembershipClientInterface} always gives a verdict.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param mixed  $value       The code to validate; accepts string, int, or BackedEnum
     *
     * @return bool|null the cached or fresh verdict, or null when the inner client gave none
     */
    public function checkCode(string $valueSetUrl, mixed $value): ?bool
    {
        $resolve = fn (): ?bool => $this->inner instanceof FHIRTerminologyMembershipClientInterface
            ? $this->inner->checkCode($valueSetUrl, $value)
            : $this->inner->validateCode($valueSetUrl, $value);

        $valueStr = $this->valueToString($value);
        if ($valueStr === null) {
            return $resolve();
        }

        return $this->remember(md5(self::MEMBERSHIP_KEY_VERSION . "code::{$valueSetUrl}::{$valueStr}"), $resolve);
    }

    /**
     * Converts a validateCode value to a string cache-key fragment, or null when uncacheable.
     *
     * Returns null for null, empty string, and non-backed-enum objects so the caller can bypass
     * the cache and delegate directly.
     *
     * @param mixed $value The raw value passed to validateCode()
     *
     * @return string|null String representation suitable for a cache key, or null if uncacheable
     */
    private function valueToString(mixed $value): ?string
    {
        if ($value instanceof \BackedEnum) {
            return (string) $value->value;
        }

        if (is_string($value) && $value !== '') {
            return $value;
        }

        if (is_int($value)) {
            return (string) $value;
        }

        return null;
    }

    /**
     * Returns true when the system+code pair is a valid member of the named value set, caching the result.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param string $system      The coding system URI (e.g. 'http://loinc.org')
     * @param string $code        The code within that system
     *
     * @return bool True when the coding is a valid member, false otherwise
     */
    public function validateCoding(string $valueSetUrl, string $system, string $code): bool
    {
        return $this->checkCoding($valueSetUrl, $system, $code) === true;
    }

    /**
     * Whether a system+code pair is in the value set, or null when the inner client gave no answer; verdicts are cached.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param string $system      The coding system URI (e.g. 'http://loinc.org')
     * @param string $code        The code within that system
     *
     * @return bool|null the cached or fresh verdict, or null when the inner client gave none
     */
    public function checkCoding(string $valueSetUrl, string $system, string $code): ?bool
    {
        return $this->remember(
            md5(self::MEMBERSHIP_KEY_VERSION . "coding::{$valueSetUrl}::{$system}|{$code}"),
            fn (): ?bool => $this->inner instanceof FHIRTerminologyMembershipClientInterface
                ? $this->inner->checkCoding($valueSetUrl, $system, $code)
                : $this->inner->validateCoding($valueSetUrl, $system, $code),
        );
    }

    /**
     * Validates the system+code pair and checks the provided display, caching the full result.
     *
     * The cache key includes the display string so different display variants are cached separately.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param string $system      The coding system URI (e.g. 'http://loinc.org')
     * @param string $code        The code within that system
     * @param string $display     The display string to validate against the canonical display
     *
     * @return CodingValidationResult Validity flag and optional corrected display string
     */
    public function validateCodingWithDisplay(
        string $valueSetUrl,
        string $system,
        string $code,
        string $display,
    ): CodingValidationResult {
        $key = md5("display::{$valueSetUrl}::{$system}|{$code}|{$display}");

        return $this->rememberResult($key, fn () => $this->inner->validateCodingWithDisplay($valueSetUrl, $system, $code, $display));
    }

    /**
     * Returns a cached membership verdict for the given key, resolving and storing it on a miss.
     *
     * Checks the in-process array first, then the PSR-6 pool (if configured), then calls
     * $resolve(). A verdict is written to both layers; a null (no answer) is returned uncached.
     *
     * @param string            $key     MD5 cache key unique to the operation and its arguments
     * @param \Closure(): ?bool $resolve Callable that performs the actual terminology lookup
     *
     * @return bool|null The cached or freshly resolved verdict, or null when the lookup gave none
     */
    private function remember(string $key, \Closure $resolve): ?bool
    {
        if (array_key_exists($key, $this->inProcess)) {
            return (bool) $this->inProcess[$key];
        }

        $cacheItem = $this->cache?->getItem($key);
        if ($cacheItem !== null && $cacheItem->isHit()) {
            $result                = (bool) $cacheItem->get();
            $this->inProcess[$key] = $result;

            return $result;
        }

        $result = $resolve();
        if ($result === null) {
            return null;
        }

        $this->inProcess[$key] = $result;

        if ($this->cache !== null && $cacheItem !== null) {
            $cacheItem->set($result);
            $cacheItem->expiresAfter($this->ttl > 0 ? $this->ttl : null);
            $this->cache->save($cacheItem);
        }

        return $result;
    }

    /**
     * Returns a cached CodingValidationResult for the given key, resolving and storing it on a miss.
     *
     * Guards against stale or wrong-typed PSR-6 entries: a cache hit that does not deserialise
     * to a CodingValidationResult is treated as a miss and the inner client is called again.
     *
     * @param string   $key     MD5 cache key unique to the operation and its arguments
     * @param \Closure $resolve Callable that performs the actual terminology lookup
     *
     * @return CodingValidationResult The cached or freshly resolved result
     */
    private function rememberResult(string $key, \Closure $resolve): CodingValidationResult
    {
        $inProcess = $this->inProcess[$key] ?? null;
        if ($inProcess instanceof CodingValidationResult) {
            return $inProcess;
        }

        if ($this->cache !== null) {
            $cacheItem = $this->cache->getItem($key);
            if ($cacheItem->isHit()) {
                $cached = $cacheItem->get();
                if ($cached instanceof CodingValidationResult) {
                    $this->inProcess[$key] = $cached;

                    return $cached;
                }
            }

            $result                = $resolve();
            $this->inProcess[$key] = $result;
            $cacheItem->set($result);
            $cacheItem->expiresAfter($this->ttl > 0 ? $this->ttl : null);
            $this->cache->save($cacheItem);

            return $result;
        }

        $result                = $resolve();
        $this->inProcess[$key] = $result;

        return $result;
    }
}
