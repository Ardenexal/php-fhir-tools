<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ConditionResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ListResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRReferenceResolverInterface;
use Ardenexal\FHIRTools\Component\Validation\SliceReferenceResolver;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * A reference in a sliced element resolves to a contained resource, a Bundle entry, or what the
 * configured resolver answers, in that order.
 */
#[CoversClass(SliceReferenceResolver::class)]
final class SliceReferenceResolverTest extends TestCase
{
    /**
     * A local reference names a resource in the `contained` of the resource holding it; inside a
     * contained resource it names a sibling, or the container for a bare `#`.
     */
    public function testALocalReferenceResolvesToAContainedResource(): void
    {
        $condition = new ConditionResource(id: 'c1');
        $sibling   = new PatientResource(id: 'p1');
        $list      = new ListResource(contained: [$condition, $sibling]);

        $resolver = new SliceReferenceResolver($list, '');
        self::assertSame($condition, $resolver->resolve(new Reference(reference: '#c1')));
        self::assertNull($resolver->resolve(new Reference(reference: '#missing')));

        $inContained = new SliceReferenceResolver($list, 'contained[0]');
        self::assertSame($sibling, $inContained->resolve(new Reference(reference: '#p1')));
        self::assertSame($list, $inContained->resolve(new Reference(reference: '#')));
    }

    /**
     * Inside a Bundle entry, a relative or absolute reference resolves to the one entry it names; a
     * local reference still resolves within the entry's own resource.
     */
    public function testAReferenceInsideABundleEntryResolvesToAnotherEntry(): void
    {
        $patient   = new PatientResource(id: '1');
        $condition = new ConditionResource(id: 'c1');
        $list      = new ListResource(id: 'l1', contained: [$condition]);
        $bundle    = new BundleResource(entry: [
            new BundleEntry(fullUrl: new UriPrimitive(value: 'http://example.org/fhir/List/l1'), resource: $list),
            new BundleEntry(fullUrl: new UriPrimitive(value: 'http://example.org/fhir/Patient/1'), resource: $patient),
            new BundleEntry(fullUrl: new UriPrimitive(value: 'urn:uuid:4f3a0c1e-8f2b-4b8e-9d1a-2c5e7f9a0b1c'), resource: new PatientResource()),
        ]);

        $resolver = new SliceReferenceResolver($bundle, 'entry[0].resource');
        self::assertSame($patient, $resolver->resolve(new Reference(reference: 'Patient/1')));
        self::assertSame($patient, $resolver->resolve(new Reference(reference: 'http://example.org/fhir/Patient/1')));
        self::assertSame($bundle->entry[2]->resource, $resolver->resolve(new Reference(reference: 'urn:uuid:4f3a0c1e-8f2b-4b8e-9d1a-2c5e7f9a0b1c')));
        self::assertSame($condition, $resolver->resolve(new Reference(reference: '#c1')));
        self::assertNull($resolver->resolve(new Reference(reference: 'Patient/2')));
    }

    /**
     * What neither contained resources nor the Bundle resolve goes to the configured resolver.
     */
    public function testAnythingElseGoesToTheConfiguredResolver(): void
    {
        $patient    = new PatientResource(id: 'x');
        $configured = new class ($patient) implements FHIRReferenceResolverInterface {
            public function __construct(private readonly object $patient)
            {
            }

            public function resolve(object $reference): ?object
            {
                return $reference instanceof Reference && $reference->reference === 'Patient/x' ? $this->patient : null;
            }
        };

        $resolver = new SliceReferenceResolver(new ListResource(), '', $configured);
        self::assertSame($patient, $resolver->resolve(new Reference(reference: 'Patient/x')));
        self::assertNull($resolver->resolve(new Reference(reference: 'Patient/y')));
    }
}
