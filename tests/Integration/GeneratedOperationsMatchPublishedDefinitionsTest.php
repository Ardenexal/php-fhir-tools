<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\OperationOutputShape;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Diffs every generated operation class against the OperationDefinition it was generated from.
 *
 * `OperationFixtureFidelityTest` proved two hand-written `$lookup` classes this way and caught real
 * defects by mutation. The argument generalises rather than expires: 154 operations and ~830
 * parameters is transcription at a volume where a dropped parameter, a widened cardinality or a
 * mistyped `part` group produces output that compiles, passes static analysis, and is wrong on the
 * wire. Membership, declaration order, `use`, `min`, `max`, `type` and the nesting followed through
 * `partClass` are each diffed against the published definition.
 *
 * **Declaration order is a contract.** `Parameters.parameter` is an ordered wire list, so the order
 * of the generated constructor's promoted properties is the order the mapper emits. Definition order
 * is the only ordering with an external authority behind it. If the generator ever sorts — required
 * first, alphabetically, anything — this test fails and the generator is what should change.
 *
 * Nesting is walked through `partClass` rather than by reconstructing class names from paths. R5's
 * `$translate-id` publishes parameters literally named `targetIdentifier.period` and
 * `targetIdentifer.preferred` (the typo is in the specification), so a dot-joined path is not
 * unambiguously splittable and a name-reconstructing check would be resolving guesses.
 *
 * What fails this test: a parameter dropped or added, parameters emitted in any order other than the
 * definition's, a cardinality or type transcribed wrongly, a `partClass` pointing at another
 * operation's or another version's class, a nested group emitted with no definition behind it, a
 * property whose `phpName` disagrees with the identifier it is attached to, and an operation holder
 * copy-pasted between versions (the invocation levels genuinely differ).
 *
 * @see GeneratedOperationClassCountMatchesParameterPathsTest for how many classes must exist
 */
final class GeneratedOperationsMatchPublishedDefinitionsTest extends TestCase
{
    /**
     * Published operations across the three versions: 47 + 47 + 60.
     */
    private const int PUBLISHED_OPERATIONS = 154;

    /**
     * Top-level payloads (57 + 57 + 72) plus nested `part[]` classes (11 + 11 + 13).
     */
    private const int PUBLISHED_PAYLOAD_CLASSES = 186;

    private const int PUBLISHED_NESTED_CLASSES = 35;

    /**
     * Every payload class of one operation matches the definition, nesting included.
     */
    #[DataProvider('operationProvider')]
    public function testPayloadClassesMatchTheDefinition(string $version, string $url): void
    {
        $definition = GeneratedOperationCorpus::operations($version)[$url];
        $payloads   = GeneratedOperationCorpus::payloadClassesFor($version, $url);
        $visited    = [];

        foreach ($payloads as $class => $payload) {
            // Roots only; the nested classes are reached by following `partClass`, which is the same
            // path the mapper takes. Anything unreachable that way shows up in the set equality below.
            if ($payload->path !== '') {
                continue;
            }

            $expected = GeneratedOperationCorpus::parameters($definition, $payload->use);

            self::assertNotSame(
                [],
                $expected,
                sprintf('%s exists but the definition declares no "%s" parameters.', $class, $payload->use),
            );

            self::assertPayloadMatches($class, $payloads, $expected, [], $version, $url, $visited);
        }

        $discovered = array_keys($payloads);
        $reached    = array_keys($visited);
        sort($discovered);
        sort($reached);

        self::assertSame(
            $discovered,
            $reached,
            sprintf(
                '%s %s: the classes on disk and the classes reachable by following partClass differ — '
                . 'a nested group is either orphaned or wired to the wrong parent.',
                $version,
                $url,
            ),
        );
    }

    /**
     * The holder carries the definition's identity and invocation levels.
     *
     * Invocation levels differ between versions for the same operation — R4 `$lookup` is type-level
     * only, R5 adds instance-level — so a holder copy-pasted across versions passes every parameter
     * assertion in this file and fails here.
     */
    #[DataProvider('operationProvider')]
    public function testHolderMatchesTheDefinition(string $version, string $url): void
    {
        $definition = GeneratedOperationCorpus::operations($version)[$url];

        $holders = array_filter(
            GeneratedOperationCorpus::holderClasses($version),
            static fn (FhirOperation $holder): bool => $holder->url === $url,
        );

        self::assertCount(1, $holders, sprintf('%s %s should have exactly one holder.', $version, $url));

        $holder = reset($holders);
        self::assertInstanceOf(FhirOperation::class, $holder);

        self::assertSame($definition['code'], $holder->code, sprintf('%s code', $url));
        self::assertSame($version, $holder->version, sprintf('%s targets the wrong package.', $url));
        self::assertSame($definition['resource'], $holder->resource, sprintf('%s resource', $url));
        self::assertSame($definition['instance'], $holder->instance, sprintf('%s instance-level invocation', $url));
        self::assertSame($definition['type'], $holder->type, sprintf('%s type-level invocation', $url));
        self::assertSame($definition['system'], $holder->system, sprintf('%s system-level invocation', $url));

        // The holder's declared shape and the emitted classes have to agree: an Output class exists
        // exactly when the response is a real `Parameters`, and a bare-resource holder points at a
        // model class the mapper returns untouched.
        $outputs = array_filter(
            GeneratedOperationCorpus::payloadClassesFor($version, $url),
            static fn (FhirOperationPayload $payload): bool => $payload->use === 'out' && $payload->path === '',
        );

        self::assertCount(
            $holder->outputShape === OperationOutputShape::Parameters ? 1 : 0,
            $outputs,
            sprintf('%s declares shape "%s" but the emitted Output classes disagree.', $url, $holder->outputShape->value),
        );
    }

    /**
     * The corpus under test is the whole published one, not a subset a broken traversal shrank to.
     */
    public function testTheCorpusCoversEveryPublishedOperationAndClass(): void
    {
        self::assertGreaterThanOrEqual(
            self::PUBLISHED_OPERATIONS,
            count(iterator_to_array(self::operationProvider())),
        );

        $payloads = 0;
        $nested   = 0;

        foreach (GeneratedOperationCorpus::VERSIONS as $version) {
            foreach (GeneratedOperationCorpus::payloadClasses($version) as $payload) {
                ++$payloads;

                if ($payload->path !== '') {
                    ++$nested;
                }
            }
        }

        self::assertGreaterThanOrEqual(self::PUBLISHED_PAYLOAD_CLASSES, $payloads);
        self::assertGreaterThanOrEqual(self::PUBLISHED_NESTED_CLASSES, $nested);
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function operationProvider(): iterable
    {
        foreach (GeneratedOperationCorpus::VERSIONS as $version) {
            foreach (array_keys(GeneratedOperationCorpus::operations($version)) as $url) {
                yield $version . ' ' . $url => [$version, $url];
            }
        }
    }

    /**
     * Diff one payload class against the definition parameters it stands for, then recurse.
     *
     * @param class-string                              $class
     * @param array<class-string, FhirOperationPayload> $payloads all payloads of this operation
     * @param list<array<string, mixed>>                $expected definition parameters, in published order
     * @param list<string>                              $path
     * @param array<class-string, true>                 $visited
     */
    private static function assertPayloadMatches(
        string $class,
        array $payloads,
        array $expected,
        array $path,
        string $version,
        string $url,
        array &$visited,
    ): void {
        $visited[$class] = true;
        $label           = sprintf('%s %s (%s)', $version, GeneratedOperationCorpus::shortName($class), implode('.', $path));

        $payload = $payloads[$class];

        self::assertSame($version, $payload->version, sprintf('%s targets the wrong package.', $label));
        self::assertSame($url, $payload->operationUrl, sprintf('%s belongs to another operation.', $label));
        self::assertSame(implode('.', $path), $payload->path, sprintf('%s declares the wrong path.', $label));

        $actual = self::declaredParameters($class);

        // Order, not just membership. See the class docblock: `Parameters.parameter` is ordered on the
        // wire, so definition order is what the generated constructor must reproduce.
        self::assertSame(
            array_map(static fn (array $parameter): mixed => $parameter['name'], $expected),
            array_map(static fn (FhirOperationParameter $parameter): string => $parameter->name, $actual),
            sprintf(
                '%s parameters differ from the published definition in membership or order. Declaration '
                . 'order must follow the definition.',
                $label,
            ),
        );

        foreach ($expected as $index => $definitionParameter) {
            $attribute = $actual[$index];
            $name      = $attribute->name;

            self::assertSame($definitionParameter['use'], $attribute->use, sprintf('%s "%s" use', $label, $name));
            self::assertSame($definitionParameter['min'], $attribute->min, sprintf('%s "%s" min', $label, $name));
            self::assertSame((string) $definitionParameter['max'], $attribute->max, sprintf('%s "%s" max', $label, $name));
            self::assertSame(
                $definitionParameter['type'] ?? null,
                $attribute->type,
                sprintf('%s "%s" type', $label, $name),
            );

            $parts = GeneratedOperationCorpus::parts($definitionParameter);

            if ($parts === []) {
                self::assertNull(
                    $attribute->partClass,
                    sprintf('%s "%s" has no parts in the definition but points at a part class.', $label, $name),
                );

                continue;
            }

            foreach ($parts as $part) {
                self::assertSame(
                    $attribute->use,
                    $part['use'] ?? null,
                    sprintf(
                        '%s "%s.%s" declares a different `use` than its parent group. The generator '
                        . 'emits every part regardless, so this would put an IN parameter on an OUT class.',
                        $label,
                        $name,
                        is_string($part['name'] ?? null) ? $part['name'] : '?',
                    ),
                );
            }

            $partClass = $attribute->partClass;

            self::assertNotNull(
                $partClass,
                sprintf('%s "%s" has parts in the definition but no partClass — they are unreachable.', $label, $name),
            );
            self::assertArrayHasKey(
                $partClass,
                $payloads,
                sprintf('%s "%s" points at %s, which is not a payload of this operation.', $label, $name, $partClass),
            );

            self::assertPayloadMatches($partClass, $payloads, $parts, [...$path, $name], $version, $url, $visited);
        }
    }

    /**
     * The class's `#[FhirOperationParameter]` attributes in declaration order.
     *
     * Read off the constructor signature rather than `getProperties()`: for promoted properties the
     * constructor is unambiguously the declaration, and it is the signature that fixes wire order.
     * Returned as an ordered list rather than keyed by name, because keying collapses a duplicate and
     * a collapsed duplicate is exactly the silent dedup this is looking for.
     *
     * @param class-string $class
     *
     * @return list<FhirOperationParameter>
     */
    private static function declaredParameters(string $class): array
    {
        $constructor = (new \ReflectionClass($class))->getConstructor();

        self::assertNotNull($constructor, sprintf('%s has no constructor to carry parameters.', $class));

        $found = [];

        foreach ($constructor->getParameters() as $parameter) {
            $attributes = $parameter->getAttributes(FhirOperationParameter::class);

            self::assertCount(
                1,
                $attributes,
                sprintf('%s::$%s carries no #[FhirOperationParameter] and is invisible to the mapper.', $class, $parameter->getName()),
            );

            $instance = $attributes[0]->newInstance();

            // `name` and `phpName` are stored separately because the transformation is lossy and not
            // invertible (`_count`, `use`, `check-system-version`). This is where the stored one is
            // checked against the identifier it actually names, rather than assumed to agree.
            self::assertSame(
                $parameter->getName(),
                $instance->phpName,
                sprintf('%s::$%s declares a phpName that is not its own identifier.', $class, $parameter->getName()),
            );

            $found[] = $instance;
        }

        return $found;
    }
}
