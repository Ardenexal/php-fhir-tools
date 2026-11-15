<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Symfony\Component\String\Slugger\AsciiSlugger;

use function Symfony\Component\String\u;

/**
 * Derives legal, collision-free PHP identifiers from FHIR operation and parameter names.
 *
 * FHIR names are not PHP identifiers and cannot be made into them by any single transformation.
 * Across the three core packages the corpus contains hyphens (`validate-code`, `check-system-version`),
 * leading underscores (`_count`, `_since`), dots (`targetIdentifier.period`), PHP reserved words
 * (`use`, `return`, `default`, `abstract`), and — in R5 — a published typo (`targetIdentifer.preferred`)
 * that must be preserved verbatim on the wire.
 *
 * ## Why this is not `FHIRValueSetGenerator`'s job
 *
 * The obvious move is to reuse that generator's naming. It cannot be reused: `getEnumName()` produces
 * **UPPER_SNAKE** enum-case style, and the guards live inside a private method. Only its
 * `slugger->slug()` call — the symbol-mapping step — is reusable, and that is what this class uses.
 *
 * ## Collisions throw
 *
 * `.goat-flow/learning-loop/footguns/valueset-enum-case-naming.md` records the same slugging surface
 * silently *skipping* a colliding name, which made a legal coded value unrepresentable with no error.
 * Silent dedup is the dangerous sibling of an outright crash, so every collision here is fatal:
 * a generator that quietly drops one of two operations produces output that looks complete.
 *
 * @author Ardenexal
 */
final class OperationClassNamer
{
    /**
     * PHP reserved words that cannot stand alone as a **class** name.
     *
     * Class names only. PHP reserves neither property names nor parameter names, so
     * `public readonly ?Coding $use` is legal and `new Designation(use: $coding)` works — M01's
     * hand-written `Designation` did exactly that, with a test proving it readable by direct access,
     * nullsafe access and reflection.
     *
     * Applying this list to property names too was a real bug: it emitted `$useParameter` for
     * `designation.use`, which silently broke an M01 assertion. `assertNull($d->use)` on the
     * generated class reads a property that does not exist, gets null, and passes — green for
     * exactly the reason N28 names as the weakest proof shape. Class names genuinely need the guard
     * (`…\Designation\Use` is a fatal parse error); properties never did.
     */
    private const array RESERVED_WORDS = [
        'abstract', 'and', 'array', 'as', 'break', 'callable', 'case', 'catch', 'class', 'clone',
        'const', 'continue', 'declare', 'default', 'do', 'echo', 'else', 'elseif', 'empty',
        'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'enum', 'eval',
        'exit', 'extends', 'final', 'finally', 'fn', 'for', 'foreach', 'function', 'global', 'goto',
        'if', 'implements', 'include', 'instanceof', 'insteadof', 'interface', 'isset', 'list',
        'match', 'namespace', 'new', 'or', 'print', 'private', 'protected', 'public', 'readonly',
        'require', 'return', 'static', 'switch', 'throw', 'trait', 'try', 'unset', 'use', 'var',
        'while', 'xor', 'yield',
    ];

    private readonly AsciiSlugger $slugger;

    public function __construct()
    {
        $this->slugger = new AsciiSlugger();
    }

    /**
     * The class stem for one operation, e.g. `CodeSystemLookup` or `ValueSetValidateCode`.
     *
     * Built from `resource[0]` + `code`. M00 measured that every OperationDefinition in all three
     * core packages carries exactly one `resource` entry, so the multi- and zero-entry branches below
     * are defensive fallbacks for IG-sourced definitions rather than the common path.
     *
     * @param array<string, mixed> $definition A full `OperationDefinition` resource
     *
     * @throws GenerationException when no legal identifier can be derived
     */
    public function classStem(array $definition): string
    {
        $resources = $definition['resource'] ?? [];
        $resource  = is_array($resources) && isset($resources[0]) && is_string($resources[0])
            ? $resources[0]
            : '';

        $code = is_string($definition['code'] ?? null) ? $definition['code'] : '';

        if ($code === '') {
            // `id` is the documented tie-breaker: a definition with no code cannot be invoked, but
            // it can still be present in a package, and silently skipping it would hide that.
            $code = is_string($definition['id'] ?? null) ? $definition['id'] : '';
        }

        $stem = $this->guardClassName($this->pascal($resource) . $this->pascal($code));

        if ($stem === '') {
            throw GenerationException::operationNamingFailed(sprintf('Cannot derive a class name for OperationDefinition "%s": resource and code both produce an empty identifier.', is_string($definition['url'] ?? null) ? $definition['url'] : '(no url)'));
        }

        return $stem;
    }

    /**
     * The nested class name for a `part[]` group, keyed by its parameter path.
     *
     * The path incorporates `use`, not just the names: `$lookup` declares `property` as both an
     * `in` `code` parameter and an `out` backbone group, so a name-keyed scheme silently resolves
     * one to the other (M01 note N3, where a test hit exactly this before any class existed).
     *
     * @param list<string> $path Parameter names from the operation root, e.g. ['property', 'subproperty']
     *
     * @throws GenerationException when no legal identifier can be derived
     */
    public function partClassName(string $use, array $path): string
    {
        $segments = array_map(fn (string $segment): string => $this->pascal($segment), $path);
        $name     = $this->guardClassName($this->pascal($use) . implode('', $segments));

        if ($name === '' || $segments === []) {
            throw GenerationException::operationNamingFailed(sprintf('Cannot derive a nested class name for the "%s" parameter path [%s].', $use, implode('.', $path)));
        }

        return $name;
    }

    /**
     * The PHP property name for a parameter's wire name.
     *
     * Never derivable in reverse, which is why generated classes store both (M01 note N7): `_count`
     * and `count` both yield `count`, and `targetIdentifier.period` yields `targetIdentifierPeriod`.
     * Reserved words are **not** escaped here — `use` yields `use`, because a property named `$use`
     * is legal PHP. See {@see self::RESERVED_WORDS}, which is class-names-only for that reason.
     *
     * @throws GenerationException when no legal identifier can be derived
     */
    public function propertyName(string $wireName): string
    {
        $name = $this->camel($wireName);

        if ($name === '') {
            throw GenerationException::operationNamingFailed(sprintf('Parameter name "%s" produces an empty PHP identifier.', $wireName));
        }

        // Numeric-leading guard: `2fa` would be a parse error. Ported from the enum-case footgun,
        // where the same slugging surface crashed on codes it could not turn into identifiers.
        // No reserved-word guard here — see RESERVED_WORDS. A property named `use` is legal, and
        // escaping it silently diverges the generated class from what callers expect.
        if (ctype_digit($name[0])) {
            $name = 'p' . ucfirst($name);
        }

        return $name;
    }

    /**
     * Assert a set of derived names contains no collisions, naming both sources when it does.
     *
     * @param array<string, string> $namesBySource Derived name keyed by the thing it came from
     *
     * @throws GenerationException on the first collision
     */
    public function assertNoCollisions(array $namesBySource, string $context): void
    {
        $seen = [];

        foreach ($namesBySource as $source => $name) {
            if (isset($seen[$name])) {
                throw GenerationException::operationNamingFailed(sprintf('%s: "%s" and "%s" both derive the identifier "%s". Emitting either would silently drop the other.', $context, $seen[$name], $source, $name));
            }

            $seen[$name] = $source;
        }
    }

    /**
     * Suffix a class name that collides with a PHP reserved word.
     *
     * Only class names need this. `…\Designation\Use` is a fatal parse error; `$use` is not.
     */
    private function guardClassName(string $name): string
    {
        return in_array(strtolower($name), self::RESERVED_WORDS, true) ? $name . 'Operation' : $name;
    }

    /**
     * Slug then PascalCase. The slugger is the symbol-mapping step, and handles hyphens and dots.
     */
    private function pascal(string $value): string
    {
        if ($value === '') {
            return '';
        }

        $slug = $this->slugger->slug($value, '-')->toString();

        return u($slug)->camel()->title()->toString();
    }

    private function camel(string $value): string
    {
        $pascal = $this->pascal($value);

        return $pascal === '' ? '' : lcfirst($pascal);
    }
}
