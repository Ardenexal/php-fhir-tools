<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledProfile;
use Ardenexal\FHIRTools\Component\Validation\Profile\CompositeStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\PackageDirectoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileFailure;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolution;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Profile\StructureDefinitionRegistryInterface;
use Ardenexal\FHIRTools\Component\Validation\Profile\UnreadableStructureDefinitionException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(RuntimeProfileResolver::class)]
#[CoversClass(RuntimeProfileResolution::class)]
final class RuntimeProfileResolverTest extends TestCase
{
    private const string PACKAGE = __DIR__ . '/../../Fixtures/Profile/package-r4';

    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string EXAMPLE = 'http://example.org/fhir/StructureDefinition/';

    public const string VITAL_SIGNS = 'http://hl7.org/fhir/StructureDefinition/vitalsigns';

    public const string EXTENSION = 'http://example.org/fhir/StructureDefinition/example-extension';

    private RuntimeProfileResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new RuntimeProfileResolver(new PackageDirectoryStructureDefinitionRegistry(self::PACKAGE));
    }

    public function testResolvesTwoLevelChainAncestorFirst(): void
    {
        $resolution = $this->resolver->resolve(self::CORE . 'bp', 'R4');

        self::assertTrue($resolution->isResolved(), $resolution->message);
        self::assertSame([self::CORE . 'vitalsigns', self::CORE . 'bp'], self::chainUrls($resolution));
        self::assertSame('Observation', $resolution->rootType);
        self::assertSame('resource', $resolution->kind);
        self::assertSame('R4', $resolution->fhirVersion);
    }

    public function testEachProfileInTheChainCompilesUnderItsOwnGroup(): void
    {
        $resolution = $this->resolver->resolve(self::CORE . 'bp', 'R4');

        [$vitalSigns, $bp] = $resolution->chain;
        $vitalSignsRule    = $vitalSigns->constraints()[0]->instantiate();
        $bpRule            = $bp->constraints()[0]->instantiate();

        self::assertInstanceOf(FHIRProfileConstraint::class, $vitalSignsRule);
        self::assertSame('subject', $vitalSignsRule->path);
        self::assertSame([self::CORE . 'vitalsigns'], $vitalSignsRule->groups);
        self::assertInstanceOf(FHIRProfileConstraint::class, $bpRule);
        self::assertSame('component', $bpRule->path);
        self::assertSame([self::CORE . 'bp'], $bpRule->groups);
    }

    public function testRepeatedResolutionReusesCompiledProfiles(): void
    {
        $first  = $this->resolver->resolve(self::CORE . 'bp', 'R4');
        $second = $this->resolver->resolve(self::CORE . 'vitalsigns', 'R4');

        self::assertSame($first->chain[0], $second->chain[0]);
    }

    public function testReplacedDefinitionIsCompiledAgain(): void
    {
        $definition = static fn (string $path): array => [
            'resourceType'   => 'StructureDefinition',
            'url'            => self::EXAMPLE . 'replaced',
            'version'        => '1.0.0',
            'fhirVersion'    => '4.0.1',
            'kind'           => 'resource',
            'type'           => 'Observation',
            'derivation'     => 'constraint',
            'baseDefinition' => self::CORE . 'Observation',
            'snapshot'       => ['element' => [['id' => 'Observation', 'path' => 'Observation']]],
            'differential'   => ['element' => [['id' => 'Observation.' . $path, 'path' => 'Observation.' . $path, 'min' => 1]]],
        ];
        $registry = new InMemoryStructureDefinitionRegistry([$definition('subject')]);
        $resolver = new RuntimeProfileResolver(new CompositeStructureDefinitionRegistry([
            $registry,
            new PackageDirectoryStructureDefinitionRegistry(self::PACKAGE),
        ]));

        $before = $resolver->resolve(self::EXAMPLE . 'replaced', 'R4');
        $registry->add($definition('component'));
        $after = $resolver->resolve(self::EXAMPLE . 'replaced', 'R4');

        self::assertSame('subject', $before->chain[0]->constraints()[0]->arguments['path'] ?? null);
        self::assertSame('component', $after->chain[0]->constraints()[0]->arguments['path'] ?? null);
    }

    public function testResolvesExtensionDefinition(): void
    {
        $resolution = $this->resolver->resolve(self::EXAMPLE . 'example-extension', 'R4');

        self::assertTrue($resolution->isResolved(), $resolution->message);
        self::assertSame([self::EXAMPLE . 'example-extension'], self::chainUrls($resolution));
        self::assertSame('Extension', $resolution->rootType);
        self::assertSame('complex-type', $resolution->kind);
    }

    public function testBaseDefinitionResolvesToEmptyChain(): void
    {
        $resolution = $this->resolver->resolve(self::CORE . 'Observation', 'R4');

        self::assertTrue($resolution->isResolved());
        self::assertSame([], $resolution->chain);
        self::assertSame('Observation', $resolution->rootType);
    }

    public function testVersionedCanonicalSelectsThatBusinessVersion(): void
    {
        $latest = $this->resolver->resolve(self::EXAMPLE . 'versioned', 'R4');
        $pinned = $this->resolver->resolve(self::EXAMPLE . 'versioned|1.0.0', 'R4');

        self::assertSame('component', $latest->chain[0]->constraints()[0]->arguments['path'] ?? null);
        self::assertSame('subject', $pinned->chain[0]->constraints()[0]->arguments['path'] ?? null);
    }

    /**
     * @return iterable<string, array{string, RuntimeProfileFailure, string}>
     */
    public static function failures(): iterable
    {
        yield 'unknown URL' => [self::EXAMPLE . 'nope', RuntimeProfileFailure::Unknown, self::EXAMPLE . 'nope'];
        yield 'unknown business version' => [self::EXAMPLE . 'versioned|9.0.0', RuntimeProfileFailure::Unknown, self::EXAMPLE . 'versioned|9.0.0'];
        yield 'no snapshot' => [self::EXAMPLE . 'no-snapshot', RuntimeProfileFailure::NoSnapshot, self::EXAMPLE . 'no-snapshot'];
        yield 'missing parent' => [self::EXAMPLE . 'orphan', RuntimeProfileFailure::UnresolvedParent, self::EXAMPLE . 'missing-parent'];
        yield 'logical model' => [self::EXAMPLE . 'logical', RuntimeProfileFailure::UnsupportedKind, self::EXAMPLE . 'logical'];
        yield 'parent cycle' => [self::EXAMPLE . 'loop-a', RuntimeProfileFailure::CircularParent, self::EXAMPLE . 'loop-a'];
    }

    #[DataProvider('failures')]
    public function testReportsTypedFailure(string $url, RuntimeProfileFailure $failure, string $failedUrl): void
    {
        $resolution = $this->resolver->resolve($url, 'R4');

        self::assertFalse($resolution->isResolved());
        self::assertSame($failure, $resolution->failure);
        self::assertSame($failedUrl, $resolution->failedUrl);
        self::assertSame([], $resolution->chain);
        self::assertNotSame('', $resolution->message);
    }

    public function testReportsFhirVersionMismatchWhenUrlExistsOnlyForOtherReleases(): void
    {
        $resolution = $this->resolver->resolve(self::CORE . 'bp', 'R5');

        self::assertSame(RuntimeProfileFailure::FhirVersionMismatch, $resolution->failure);
        self::assertStringContainsString('registered for R4, not for R5', $resolution->message);
    }

    public function testParentChainMayCrossRegistries(): void
    {
        $ig = new InMemoryStructureDefinitionRegistry([[
            'resourceType'   => 'StructureDefinition',
            'url'            => self::EXAMPLE . 'ig-bp',
            'fhirVersion'    => '4.0.1',
            'kind'           => 'resource',
            'type'           => 'Observation',
            'derivation'     => 'constraint',
            'baseDefinition' => self::CORE . 'bp',
            'snapshot'       => ['element' => [['id' => 'Observation', 'path' => 'Observation']]],
            'differential'   => ['element' => [['id' => 'Observation.status', 'path' => 'Observation.status', 'min' => 1]]],
        ]]);
        $resolver = new RuntimeProfileResolver(new CompositeStructureDefinitionRegistry([
            $ig,
            new PackageDirectoryStructureDefinitionRegistry(self::PACKAGE),
        ]));

        $resolution = $resolver->resolve(self::EXAMPLE . 'ig-bp', 'R4');

        self::assertSame([self::CORE . 'vitalsigns', self::CORE . 'bp', self::EXAMPLE . 'ig-bp'], self::chainUrls($resolution));
    }

    /**
     * A definition that cannot be read anywhere in the chain makes the profile unusable, and an
     * extension definition that cannot be read is one nothing is known about; neither throws.
     */
    public function testAnUnreadableDefinitionIsAFailureNotAnException(): void
    {
        $package = new PackageDirectoryStructureDefinitionRegistry(self::PACKAGE);
        self::assertNotNull((new RuntimeProfileResolver($package))->resolveExtension(self::EXTENSION, 'R4'));

        $registry = new class ($package) implements StructureDefinitionRegistryInterface {
            public function __construct(private readonly StructureDefinitionRegistryInterface $inner)
            {
            }

            public function find(string $url, string $fhirVersion, ?string $version = null): ?array
            {
                if (in_array($url, [RuntimeProfileResolverTest::VITAL_SIGNS, RuntimeProfileResolverTest::EXTENSION], true)) {
                    throw new UnreadableStructureDefinitionException(sprintf('Cannot read the file for "%s".', $url));
                }

                return $this->inner->find($url, $fhirVersion, $version);
            }

            public function fhirVersionsFor(string $url): array
            {
                return $this->inner->fhirVersionsFor($url);
            }
        };
        $resolver = new RuntimeProfileResolver($registry);

        $resolution = $resolver->resolve(self::CORE . 'bp', 'R4');

        self::assertSame(RuntimeProfileFailure::Unreadable, $resolution->failure);
        self::assertSame('Cannot read the file for "' . self::VITAL_SIGNS . '".', $resolution->message);
        self::assertNull($resolver->resolveExtension(self::EXTENSION, 'R4'));
    }

    public function testRejectsUnsupportedFhirRelease(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->resolver->resolve(self::CORE . 'bp', 'STU3');
    }

    /**
     * @return list<string>
     */
    private static function chainUrls(RuntimeProfileResolution $resolution): array
    {
        return array_map(static fn (CompiledProfile $profile): string => $profile->profileUrl, $resolution->chain);
    }
}
