<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Profile\StructureDefinitionCompiler;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationOptions;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * A profile that adds a slice to its parent's ordered slicing holds that slice to its place among
 * the parent's slices, and a pair of the parent's own slices out of order is reported once.
 */
#[CoversClass(StructureDefinitionCompiler::class)]
#[CoversClass(FHIRSliceConstraintValidator::class)]
final class RuntimeInheritedOrderedSlicingTest extends TestCase
{
    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string PARENT = 'http://example.org/fhir/StructureDefinition/ordered-parent';

    private const string CHILD = 'http://example.org/fhir/StructureDefinition/ordered-child';

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testItemsInDeclarationOrderHaveNoFindings(): void
    {
        self::assertSame([], $this->findings(['urn:first', 'urn:second', 'urn:third']));
    }

    public function testTheChildsSliceMustComeAfterTheParentsSlices(): void
    {
        self::assertSame(
            [[self::CHILD, 'entry[1]', 'Property "entry" uses ordered slicing: this item matches slice "first", which must come before slice "third" of an earlier item.']],
            $this->findings(['urn:third', 'urn:first']),
        );
    }

    public function testTheParentsOwnSlicesOutOfOrderAreReportedOnce(): void
    {
        self::assertSame(
            [[self::PARENT, 'entry[1]', 'Property "entry" uses ordered slicing: this item matches slice "first", which must come before slice "second" of an earlier item.']],
            $this->findings(['urn:second', 'urn:first']),
        );
    }

    /**
     * Ordered-slicing findings for a Bundle with one entry per full URL, as [group, path, message].
     *
     * @param list<string> $fullUrls
     *
     * @return list<array{string, string, string}>
     */
    private function findings(array $fullUrls): array
    {
        $bundle = [
            'resourceType' => 'Bundle',
            // Named in meta.profile, so the parent's rules run in their own group beside the child's
            'meta'         => ['profile' => [self::CHILD]],
            'type'         => 'collection',
            'entry'        => array_map(static fn (string $url): array => ['fullUrl' => $url], $fullUrls),
        ];

        $found = array_map(
            static fn (FHIRValidationViolation $v): array => [(string) $v->profileGroup, $v->path, $v->message],
            array_values(array_filter(
                FHIRValidationServiceBuilder::create()->withStructureDefinitionRegistry(self::registry())->build()
                    ->validateWithOptions($this->serializer->deserialize((string) json_encode($bundle)), new FHIRValidationOptions(profilesFromMeta: true))->violations,
                static fn (FHIRValidationViolation $v): bool => str_contains($v->message, 'ordered slicing'),
            )),
        );
        sort($found);

        return $found;
    }

    /**
     * The parent slices `entry` by full URL with ordered slicing into "first" and "second"; the child
     * restates "second" with a maximum, which keeps it the parent's slice, and adds "third" after them.
     */
    private static function registry(): InMemoryStructureDefinitionRegistry
    {
        $slicing = ['discriminator' => [['type' => 'value', 'path' => 'fullUrl']], 'rules' => 'open', 'ordered' => true];
        $parent  = [
            ['id' => 'Bundle', 'path' => 'Bundle'],
            ['id' => 'Bundle.entry', 'path' => 'Bundle.entry', 'slicing' => $slicing],
            ...self::slice('first'),
            ...self::slice('second'),
        ];
        $added = [['id' => 'Bundle.entry:second', 'path' => 'Bundle.entry', 'sliceName' => 'second', 'max' => '1'], ...self::slice('third')];

        return new InMemoryStructureDefinitionRegistry([
            [
                'resourceType' => 'StructureDefinition',
                'url'          => self::CORE . 'Bundle',
                'fhirVersion'  => '4.0.1',
                'kind'         => 'resource',
                'type'         => 'Bundle',
                'derivation'   => 'specialization',
                'snapshot'     => ['element' => [['id' => 'Bundle', 'path' => 'Bundle']]],
            ],
            self::profile(self::PARENT, self::CORE . 'Bundle', $parent, $parent),
            self::profile(self::CHILD, self::PARENT, [['id' => 'Bundle', 'path' => 'Bundle'], ...$added], [...$parent, ...self::slice('third')]),
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private static function slice(string $name): array
    {
        return [
            ['id' => "Bundle.entry:{$name}", 'path' => 'Bundle.entry', 'sliceName' => $name],
            ['id' => "Bundle.entry:{$name}.fullUrl", 'path' => 'Bundle.entry.fullUrl', 'fixedUri' => "urn:{$name}"],
        ];
    }

    /**
     * @param list<array<string, mixed>> $differential
     * @param list<array<string, mixed>> $snapshot
     *
     * @return array<string, mixed>
     */
    private static function profile(string $url, string $base, array $differential, array $snapshot): array
    {
        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'fhirVersion'    => '4.0.1',
            'kind'           => 'resource',
            'type'           => 'Bundle',
            'derivation'     => 'constraint',
            'baseDefinition' => $base,
            'snapshot'       => ['element' => $snapshot],
            'differential'   => ['element' => $differential],
        ];
    }
}
