<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Command;

use Ardenexal\FHIRTools\Component\CodeGeneration\Command\FHIRModelGeneratorCommand;
use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * One unusable OperationDefinition must not cost the whole version's output.
 *
 * The command deletes `Models/src/{version}` in its Clear phase, *before* generating, and writes
 * files back only in its final phase. Every generation phase but one therefore contains per-item
 * failures in the `ErrorCollector` and keeps going — `buildClasses` via
 * `generateModelClassWithErrorHandling`, `buildExtensions`, `buildProfiles` and `buildEnums` all
 * do. `buildOperations` did not, so a single malformed definition in any loaded package aborted
 * before the write phase and left the version directory deleted.
 *
 * Two properties are asserted together, and they pull in opposite directions:
 *   - the failure must be CONTAINED, so the other definitions still reach the context; and
 *   - the failure must still be an ERROR, not a warning, so `execute()`'s `hasErrors()` gate
 *     returns `Command::FAILURE`. Containment alone would silently ship a missing operation class,
 *     which is the very harm the generator's fail-loud stance exists to prevent.
 */
final class BuildOperationsErrorContainmentTest extends TestCase
{
    /**
     * A `kind: operation` definition the generator refuses, by design.
     *
     * `two-words` and `twoWords` both slug to the identifier `twoWords`, so
     * `OperationClassNamer::assertNoCollisions` throws rather than emit one and silently drop the
     * other. That is the generator's intended behaviour — the defect under test is not the throw,
     * it is that the throw used to escape `buildOperations` and abort the whole version.
     *
     * Note an empty `code` does NOT work here: the stem falls back to `resource[0]` and names
     * cleanly, which is a separate (latent) gap in the namer's guard chain.
     */
    private const MALFORMED = [
        'resourceType' => 'OperationDefinition',
        'url'          => 'http://example.test/OperationDefinition/broken',
        'name'         => 'Broken',
        'kind'         => 'operation',
        'code'         => 'broken',
        'resource'     => ['Patient'],
        'system'       => false,
        'type'         => true,
        'instance'     => false,
        'parameter'    => [
            ['name' => 'two-words', 'use' => 'in', 'min' => 0, 'max' => '1', 'type' => 'string'],
            ['name' => 'twoWords', 'use' => 'in', 'min' => 0, 'max' => '1', 'type' => 'string'],
        ],
    ];

    private const HEALTHY = [
        'resourceType' => 'OperationDefinition',
        'url'          => 'http://example.test/OperationDefinition/healthy',
        'name'         => 'Healthy',
        'kind'         => 'operation',
        'code'         => 'ping',
        'resource'     => ['Patient'],
        'system'       => false,
        'type'         => true,
        'instance'     => false,
        'parameter'    => [
            ['name' => 'input', 'use' => 'in', 'min' => 0, 'max' => '1', 'type' => 'string'],
        ],
    ];

    public function testAMalformedDefinitionIsContainedAndTheHealthyOneStillGenerates(): void
    {
        [$command, $context, $errors] = $this->commandWithDefinitions([
            self::MALFORMED['url'] => self::MALFORMED,
            self::HEALTHY['url']   => self::HEALTHY,
        ]);

        // Must not throw: an escaping exception is what left the output tree deleted.
        $this->invokeBuildOperations($command);

        self::assertNotNull(
            $context->getType(self::HEALTHY['url'] . '#operation'),
            'The healthy operation must still be generated; containment is the point of the fix.',
        );
        self::assertNull(
            $context->getType(self::MALFORMED['url'] . '#operation'),
            'The malformed operation cannot be generated, by definition.',
        );

        self::assertTrue(
            $errors->hasErrors(),
            'The failure must be recorded as an error, not a warning: execute() gates on '
            . 'hasErrors(), so a warning would exit 0 and silently ship a missing operation class.',
        );
        self::assertStringContainsString(
            self::MALFORMED['url'],
            $errors->getDetailedOutput(),
            'The recorded error must name the definition that failed, or the operator cannot act on it.',
        );
    }

    /**
     * Two definitions that derive the same class stem must not silently overwrite each other.
     *
     * `classStem` is `pascal(resource[0]) . pascal(code)`, so two OperationDefinitions with different
     * canonical URLs but the same first resource and code produce the same namespace, the same class
     * names and the same file paths. `BuilderContext::addType` keys on the URL so both survive in the
     * context; `outputGeneratedFiles` then writes both to `Operation/{Stem}/{Stem}Input.php` and the
     * second wins. The count printed at the end of the phase counted both.
     *
     * The result was a legal, invocable operation absent from the generated API, with exit code 0 and
     * a plausible-looking count — exactly what `OperationClassNamer`'s own docblock promises against
     * ("every collision here is fatal: a generator that quietly drops one of two operations produces
     * output that looks complete"). That promise only ever covered parameter names within one class.
     *
     * An IG redefining `Patient/$match` is the realistic trigger; nothing in the core packages
     * collides (R4 47 / R4B 47 / R5 60 stems, all distinct), which is why this went unnoticed.
     */
    public function testTwoDefinitionsDerivingTheSameStemAreReportedNotOverwritten(): void
    {
        $first = self::HEALTHY;

        // Same resource + same code, different URL: a downstream IG redefining a core operation.
        $second         = self::HEALTHY;
        $second['url']  = 'http://example.test/ig/OperationDefinition/ping-redefined';
        $second['name'] = 'PingRedefined';

        [$command, $context, $errors] = $this->commandWithDefinitions([
            $first['url']  => $first,
            $second['url'] => $second,
        ]);

        $this->invokeBuildOperations($command);

        self::assertTrue(
            $errors->hasErrors(),
            'A stem collision must be reported. Silently overwriting one operation with another '
            . 'produces generated output that looks complete but is missing an invocable operation.',
        );
        self::assertStringContainsString(
            $second['url'],
            $errors->getDetailedOutput(),
            'The error must name the losing definition so the operator can act on it.',
        );

        // The first claimant is kept, deterministically — not the last writer.
        self::assertNotNull($context->getType($first['url'] . '#operation'));
        self::assertNull($context->getType($second['url'] . '#operation'));
    }

    /**
     * With nothing malformed, nothing is recorded — the containment must not manufacture errors.
     */
    public function testAHealthyCorpusRecordsNoErrors(): void
    {
        [$command, $context, $errors] = $this->commandWithDefinitions([
            self::HEALTHY['url'] => self::HEALTHY,
        ]);

        $this->invokeBuildOperations($command);

        self::assertNotNull($context->getType(self::HEALTHY['url'] . '#operation'));
        self::assertFalse($errors->hasErrors(), $errors->getDetailedOutput());
    }

    /**
     * @param array<string, array<string, mixed>> $definitions
     *
     * @return array{FHIRModelGeneratorCommand, BuilderContext, ErrorCollector}
     */
    private function commandWithDefinitions(array $definitions): array
    {
        // The loader is never exercised: buildOperations reads definitions already in the
        // context. It only has to construct.
        $filesystem = new Filesystem();
        $loader     = new PackageLoader(
            new MockHttpClient(),
            sys_get_temp_dir() . '/fhir-buildoperations-test',
            new BuilderContext(),
            $filesystem,
        );

        $command = new FHIRModelGeneratorCommand($filesystem, $loader);

        $context = new BuilderContext();
        foreach ($definitions as $url => $definition) {
            $context->addDefinition($url, $definition);
        }

        $reflection = new \ReflectionClass($command);

        $contexts = $reflection->getProperty('context');
        /** @var array<string, BuilderContext> $current */
        $current       = $contexts->getValue($command);
        $current['R5'] = $context;
        $contexts->setValue($command, $current);

        $collector = new ErrorCollector();
        $reflection->getProperty('errorCollector')->setValue($command, $collector);

        return [$command, $context, $collector];
    }

    private function invokeBuildOperations(FHIRModelGeneratorCommand $command): void
    {
        (new \ReflectionMethod($command, 'buildOperations'))
            ->invoke($command, new NullOutput(), 'R5');
    }
}
