<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\BuilderContextTypeIndex;
use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\OutputShapeClassifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use PHPUnit\Framework\Assert;

/**
 * Shared access to the generated operation tree and the committed specification fixtures.
 *
 * The two checks that consume this — class-count == path-count and per-operation fidelity — need the
 * same three things and would otherwise transcribe them twice. Both halves live outside either
 * component on purpose: `CodeGeneration` must not depend on `Models` (`CodeGenerationIndependenceTest`
 * enforces it as a shipped Composer boundary), and `Serialization` cannot see `CodeGeneration`. Only
 * the monorepo-level suite has both on the autoloader.
 *
 * Specification data comes from the committed fixtures, never from `demo/var/cache/dev/.fhir/`: that
 * cache is gitignored and its contents depend on which packages the developer last pulled, so a check
 * reading it would pass or fail for reasons unrelated to the code (M01 note N4).
 *
 * Not named `*Test.php`, so PHPUnit's directory suites do not collect it.
 */
final class GeneratedOperationCorpus
{
    /** @var list<string> */
    public const array VERSIONS = ['R4', 'R4B', 'R5'];

    /** @var array<string, array<string, array<string, mixed>>> */
    private static array $manifests = [];

    /** @var array<string, array<class-string, FhirOperationPayload>> */
    private static array $payloads = [];

    /** @var array<string, array<class-string, FhirOperation>> */
    private static array $holders = [];

    /** @var array<string, BuilderContext> */
    private static array $contexts = [];

    /**
     * Every `kind = operation` definition for a version, keyed by canonical url.
     *
     * `kind = query` is excluded by design (M03) — R5 ships exactly one, and it must not appear in
     * the generated tree.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function operations(string $version): array
    {
        return array_filter(
            self::manifest($version),
            static fn (array $definition): bool => ($definition['kind'] ?? null) === 'operation',
        );
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function manifest(string $version): array
    {
        if (isset(self::$manifests[$version])) {
            return self::$manifests[$version];
        }

        $file = sprintf(
            '%s/../../src/Component/CodeGeneration/tests/Fixtures/OperationManifests/%s-operations.json',
            __DIR__,
            strtolower($version),
        );

        $contents = file_get_contents($file);

        if ($contents === false) {
            Assert::fail(sprintf('Operation manifest %s is unreadable.', $file));
        }

        /** @var array<string, array<string, mixed>> $manifest */
        $manifest = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        return self::$manifests[$version] = $manifest;
    }

    /**
     * Generated payload classes for a version, keyed by FQCN.
     *
     * @return array<class-string, FhirOperationPayload>
     */
    public static function payloadClasses(string $version): array
    {
        self::discover($version);

        return self::$payloads[$version];
    }

    /**
     * Generated operation holders for a version, keyed by FQCN.
     *
     * @return array<class-string, FhirOperation>
     */
    public static function holderClasses(string $version): array
    {
        self::discover($version);

        return self::$holders[$version];
    }

    /**
     * The payload classes belonging to one operation, keyed by FQCN.
     *
     * @return array<class-string, FhirOperationPayload>
     */
    public static function payloadClassesFor(string $version, string $operationUrl): array
    {
        return array_filter(
            self::payloadClasses($version),
            static fn (FhirOperationPayload $payload): bool => $payload->operationUrl === $operationUrl,
        );
    }

    /**
     * The wire shape of an operation's response, from the production classifier.
     *
     * Deliberately the production `OutputShapeClassifier` rather than a second transcription of the
     * un-wrap rule: a hand-copy would rot independently and start producing false failures. What keeps
     * the check honest is that the expected totals are pre-registered literals — if the classifier
     * drifts, per-operation equality still holds (both sides move together) and the literal counts are
     * what break.
     *
     * @param array<string, mixed> $definition
     */
    public static function outputShape(string $version, array $definition): string
    {
        $shape = (new OutputShapeClassifier())->classify(
            $definition,
            new BuilderContextTypeIndex(self::typeIndexContext($version)),
        );

        return $shape['shape'];
    }

    /**
     * Parameters of a definition filtered to one direction, in published order.
     *
     * @param array<string, mixed> $definition
     *
     * @return list<array<string, mixed>>
     */
    public static function parameters(array $definition, string $use): array
    {
        $parameters = $definition['parameter'] ?? [];

        if (!is_array($parameters)) {
            return [];
        }

        return array_values(array_filter(
            $parameters,
            static fn (mixed $parameter): bool => is_array($parameter) && ($parameter['use'] ?? null) === $use,
        ));
    }

    /**
     * Direct `part[]` children of a parameter, in published order.
     *
     * Not filtered by `use`: the generator does not filter them either, and a part whose `use`
     * disagreed with its parent would be a defect in the definition rather than something to hide.
     * `GeneratedOperationsMatchPublishedDefinitionsTest` asserts the agreement explicitly.
     *
     * @param array<string, mixed> $parameter
     *
     * @return list<array<string, mixed>>
     */
    public static function parts(array $parameter): array
    {
        $parts = $parameter['part'] ?? [];

        if (!is_array($parts)) {
            return [];
        }

        return array_values(array_filter($parts, static fn (mixed $part): bool => is_array($part)));
    }

    /**
     * A `BuilderContext` carrying the version's committed type index.
     *
     * Each version has its own index because the type sets genuinely differ (R4 209 types, R4B 206),
     * and sharing one would quietly resolve `kind` against the wrong package.
     */
    public static function typeIndexContext(string $version): BuilderContext
    {
        if (isset(self::$contexts[$version])) {
            return self::$contexts[$version];
        }

        $file = sprintf(
            '%s/../../src/Component/CodeGeneration/tests/Fixtures/TypeIndex/%s-type-index.json',
            __DIR__,
            strtolower($version),
        );

        $contents = file_get_contents($file);

        if ($contents === false) {
            Assert::fail(sprintf('Type index %s is unreadable.', $file));
        }

        /** @var array<string, array<string, mixed>> $definitions */
        $definitions = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        $context = new BuilderContext();
        $context->loadDefinitions($definitions);

        return self::$contexts[$version] = $context;
    }

    public static function shortName(string $class): string
    {
        $position = strrpos($class, '\\');

        return $position === false ? $class : substr($class, $position + 1);
    }

    /**
     * Index the generated tree by reflecting on every file under `Operation/`.
     *
     * Classes are resolved through `class_exists()` rather than counted as files. The distinction is
     * not pedantic: M10's live failure was a generated file whose declared namespace did not nest with
     * its directory, so it existed on disk and never autoloaded. A file-counting discovery passes that
     * bug; this one fails on it.
     */
    private static function discover(string $version): void
    {
        if (isset(self::$payloads[$version])) {
            return;
        }

        $directory = sprintf('%s/../../src/Component/Models/src/%s/Operation', __DIR__, $version);
        $files     = glob($directory . '/*/*.php');

        if ($files === false || $files === []) {
            Assert::fail(sprintf('No generated operation classes found under %s.', $directory));
        }

        $payloads = [];
        $holders  = [];

        foreach ($files as $file) {
            $class = sprintf(
                'Ardenexal\FHIRTools\Component\Models\%s\Operation\%s\%s',
                $version,
                basename(dirname($file)),
                basename($file, '.php'),
            );

            if (!class_exists($class)) {
                Assert::fail(sprintf(
                    '%s does not autoload as %s — the generated namespace and its directory disagree, '
                    . 'so the class is unusable despite the file being present.',
                    $file,
                    $class,
                ));
            }

            $reflection      = new \ReflectionClass($class);
            $payloadMarkers  = $reflection->getAttributes(FhirOperationPayload::class);
            $holderMarkers   = $reflection->getAttributes(FhirOperation::class);

            if ($payloadMarkers !== []) {
                $payloads[$class] = $payloadMarkers[0]->newInstance();

                continue;
            }

            if ($holderMarkers !== []) {
                $holders[$class] = $holderMarkers[0]->newInstance();

                continue;
            }

            Assert::fail(sprintf(
                '%s carries neither #[FhirOperation] nor #[FhirOperationPayload]. Unmarked classes are '
                . 'routed to the DataType namespace by the generator pipeline and are invisible to the '
                . 'operation normalizers.',
                $class,
            ));
        }

        self::$payloads[$version] = $payloads;
        self::$holders[$version]  = $holders;
    }
}
