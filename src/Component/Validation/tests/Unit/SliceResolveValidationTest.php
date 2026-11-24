<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ConditionResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\List\ListEntry;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\SliceReferenceResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\ResolveSlicedListFixture;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Slicing through `resolve()` follows references from where the sliced element really sits in the
 * validated resource: inside a Bundle entry it reaches the other entries, and inside a contained
 * resource it reaches that resource's siblings.
 */
#[CoversClass(FHIRSliceConstraintValidator::class)]
#[CoversClass(SliceReferenceResolver::class)]
final class SliceResolveValidationTest extends TestCase
{
    public function testReferencesInsideABundleEntryResolveToOtherEntries(): void
    {
        $bundle = self::bundle(self::list('Condition/1'));
        self::assertSame([], self::sliceFindings($bundle));

        // Two Patients and no Condition: the slice's minimum fails and closed slicing rejects both
        $bundle = self::bundle(self::list('Patient/1', 'Patient/1'));
        self::assertSame([
            ['entry[0].resource.entry', 'Property "entry" uses closed slicing but contains 2 item(s) matching no defined slice.'],
            ['entry[0].resource.entry', 'Slice "condition" on "entry" requires at least 1 item(s), but 0 matched.'],
        ], self::sliceFindings($bundle));

        // A reference to nothing in the Bundle cannot be judged, so nothing is reported
        self::assertSame([], self::sliceFindings(self::bundle(self::list('Condition/2'))));
    }

    /**
     * Each resource of the profile is checked, not only the first: the slicing used to be checked
     * once per property for the whole validation.
     */
    public function testEveryResourceOfTheProfileInABundleIsChecked(): void
    {
        $bundle = new BundleResource(entry: [new BundleEntry(resource: self::list()), new BundleEntry(resource: self::list())]);

        self::assertSame([
            ['entry[0].resource.entry', 'Slice "condition" on "entry" requires at least 1 item(s), but 0 matched.'],
            ['entry[1].resource.entry', 'Slice "condition" on "entry" requires at least 1 item(s), but 0 matched.'],
        ], self::sliceFindings($bundle));
    }

    public function testLocalReferencesInsideAContainedResourceResolveToItsSiblings(): void
    {
        $list      = self::list('#c1');
        $container = new PatientResource(id: 'p', contained: [$list, new ConditionResource(id: 'c1')]);
        self::assertSame([], self::sliceFindings($container));

        $list->entry = [new ListEntry(item: new Reference(reference: '#'))];
        self::assertSame([
            ['contained[0].entry', 'Property "entry" uses closed slicing but contains 1 item(s) matching no defined slice.'],
            ['contained[0].entry', 'Slice "condition" on "entry" requires at least 1 item(s), but 0 matched.'],
        ], self::sliceFindings($container));
    }

    private static function list(string ...$references): ResolveSlicedListFixture
    {
        $list        = new ResolveSlicedListFixture(id: 'l1');
        $list->entry = array_map(static fn (string $reference): ListEntry => new ListEntry(item: new Reference(reference: $reference)), $references);

        return $list;
    }

    private static function bundle(ResolveSlicedListFixture $list): BundleResource
    {
        return new BundleResource(entry: [
            new BundleEntry(fullUrl: new UriPrimitive(value: 'http://example.org/fhir/List/l1'), resource: $list),
            new BundleEntry(fullUrl: new UriPrimitive(value: 'http://example.org/fhir/Condition/1'), resource: new ConditionResource(id: '1')),
            new BundleEntry(fullUrl: new UriPrimitive(value: 'http://example.org/fhir/Patient/1'), resource: new PatientResource(id: '1')),
        ]);
    }

    /**
     * @return list<array{string, string}>
     */
    private static function sliceFindings(object $resource): array
    {
        $findings = [];
        foreach (FHIRValidationServiceBuilder::create()->build()->validate($resource, [ResolveSlicedListFixture::URL])->violations as $violation) {
            if (self::isSliceFinding($violation)) {
                $findings[] = [$violation->path, $violation->message];
            }
        }
        sort($findings);

        return $findings;
    }

    private static function isSliceFinding(FHIRValidationViolation $violation): bool
    {
        return str_contains($violation->message, 'slic') || str_contains($violation->message, 'Slice');
    }
}
