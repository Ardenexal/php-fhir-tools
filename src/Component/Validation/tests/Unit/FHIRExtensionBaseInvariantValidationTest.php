<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept as R4CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\Extension\ADUseExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Extension\PronounsExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive as R4CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource as R4PatientResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ListModeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ListStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ListResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use PHPUnit\Framework\TestCase;

/**
 * Extension's own invariants run on every extension, wherever it sits.
 *
 * Symfony does not cascade into extensions, so the invariants declared on the Extension class itself
 * (ext-1, and ele-1 from Element) are only reached through the extension walk in
 * FHIRValidationService. `R5.list-extension2` is the corpus case: an extension on `_status` whose only
 * value was written under an unknown property name, leaving it with neither a value nor sub-extensions.
 */
final class FHIRExtensionBaseInvariantValidationTest extends TestCase
{
    private FHIRValidationService $service;

    protected function setUp(): void
    {
        $this->service = FHIRValidationServiceBuilder::create()->build();
    }

    public function testExtensionWithNeitherValueNorSubExtensionsBreaksExt1(): void
    {
        $list = $this->list([new Extension(url: 'http://example.org/some-url')]);

        self::assertSame([['extension[0]', 'ext-1']], $this->invariantFailures($list));
    }

    public function testExtensionWithBothValueAndSubExtensionsBreaksExt1(): void
    {
        $list = $this->list([new Extension(
            url: 'http://example.org/some-url',
            extension: [new Extension(url: 'inner', value: new CodePrimitive(value: 'a'))],
            value: new CodePrimitive(value: 'b'),
        )]);

        self::assertSame([['extension[0]', 'ext-1']], $this->invariantFailures($list));
    }

    public function testExtensionOnAPrimitiveIsChecked(): void
    {
        $list                    = $this->list([]);
        $list->status            = new ListStatusType(value: 'current');
        $list->status->extension = [new Extension(url: 'http://example.org/some-url')];

        self::assertSame([['status.extension[0]', 'ext-1']], $this->invariantFailures($list));
    }

    public function testWellFormedExtensionPasses(): void
    {
        $list = $this->list([new Extension(url: 'http://example.org/some-url', value: new CodePrimitive(value: 'a'))]);

        self::assertSame([], $this->invariantFailures($list));
    }

    /**
     * A typed extension class inherits ext-1 from Extension, so it is held to it too. A complex one
     * keeps its parts as typed properties, `PronounsExtension::$valueSlice` among them, so ext-1 must
     * not read that property as the extension's own value.
     */
    public function testTypedExtensionsPassTheBaseInvariants(): void
    {
        $patient = new R4PatientResource(extension: [
            new PronounsExtension(valueSlice: new R4CodeableConcept(text: 'she/her')),
            new ADUseExtension(valueCode: new R4CodePrimitive(value: 'BAD')),
        ]);

        $baseFailures = array_filter(
            $this->service->validate($patient)->errors(),
            static fn (FHIRValidationViolation $v): bool => in_array($v->invariantKey, ['ext-1', 'ele-1'], true),
        );

        self::assertSame([], array_values($baseFailures));
    }

    /** @param list<Extension> $extensions */
    private function list(array $extensions): ListResource
    {
        return new ListResource(
            extension: $extensions,
            status: new ListStatusType(value: 'current'),
            mode: new ListModeType(value: 'working'),
        );
    }

    /** @return list<array{string, string}> path and key of every invariant failure, in report order */
    private function invariantFailures(ListResource $list): array
    {
        $failures = array_filter(
            $this->service->validate($list)->errors(),
            static fn (FHIRValidationViolation $v): bool => $v->invariantKey !== null,
        );

        return array_values(array_map(
            static fn (FHIRValidationViolation $v): array => [$v->path, (string) $v->invariantKey],
            $failures,
        ));
    }
}
