<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Support;

use function Symfony\Component\String\u;

/**
 * Case conversions for generated identifiers, written against the lowest Symfony version this
 * component declares support for.
 *
 * `AbstractString::pascal()` was only added in symfony/string 7.3 and `kebab()` in 7.2, but
 * composer.json declares `^6.4|^7.0`. Calling either directly therefore throws
 * `Call to undefined method` on every version from 6.4 through 7.2. That took out generation
 * entirely, since class naming runs for every StructureDefinition.
 *
 * The bodies below are the upstream implementations, transcribed rather than approximated:
 * symfony/string defines `pascal()` as `camel()->title()` and `kebab()` as
 * `snake()->replace('_', '-')`, both on `AbstractString` with no subclass override. Output is
 * therefore byte-identical to 7.3+ by construction, not by coincidence. That matters because
 * these strings become generated class names, and a divergence between framework versions would
 * silently rename classes rather than fail loudly.
 */
final class StringCase
{
    /**
     * PascalCase, equivalent to `u($source)->pascal()->toString()` on symfony/string 7.3+.
     *
     * Note this preserves existing runs of capitals rather than flattening them: `AUCorePatient`
     * stays `AUCorePatient`, because upstream `camel()` skips characters followed by an uppercase
     * letter.
     *
     * @param string $source raw FHIR name, code, id or element path to derive an identifier from
     *
     * @return string the same text with separators removed and each word capitalised
     */
    public static function pascal(string $source): string
    {
        return u($source)->camel()->title()->toString();
    }

    /**
     * kebab-case, equivalent to `u($source)->kebab()->toString()` on symfony/string 7.2+.
     *
     * Unused by the generators today, but present so that reaching for kebab-casing later does not
     * reintroduce the same lower-bound break.
     *
     * @param string $source raw FHIR name, code, id or element path to derive an identifier from
     *
     * @return string the same text lowercased with words joined by hyphens
     */
    public static function kebab(string $source): string
    {
        return u($source)->snake()->replace('_', '-')->toString();
    }
}
