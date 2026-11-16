<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRContextInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledExtension;
use Ardenexal\FHIRTools\Component\Metadata\Profile\ExtensionSlice;
use Ardenexal\FHIRTools\Component\Metadata\Profile\StructureDefinitionCompiler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * An extension definition compiles to the contexts its generated class carries and the value types
 * and sub-extension slices its generated constructor is built from.
 */
#[CoversClass(StructureDefinitionCompiler::class)]
#[CoversClass(CompiledExtension::class)]
#[CoversClass(ExtensionSlice::class)]
final class ExtensionCompilationTest extends TestCase
{
    private const string URL = 'http://example.org/fhir/StructureDefinition/pet';

    public function testContextsAndInvariantsComeOutInGeneratorOrder(): void
    {
        $compiled = (new StructureDefinitionCompiler())->compileExtension(self::definition([
            'context'          => [['type' => 'element', 'expression' => 'Patient'], ['type' => 'extension', 'expression' => 'http://x'], ['expression' => 'no type']],
            'contextInvariant' => ['code.empty()', ''],
        ], []));

        self::assertSame(self::URL, $compiled->url);
        self::assertSame(
            [
                [FHIRExtensionContext::class, ['type' => 'element', 'expression' => 'Patient']],
                [FHIRExtensionContext::class, ['type' => 'extension', 'expression' => 'http://x']],
                [FHIRContextInvariant::class, ['expression' => 'code.empty()']],
            ],
            array_map(static fn ($a): array => [$a->class, $a->arguments], $compiled->contexts),
        );
    }

    public function testASimpleExtensionHasItsValueTypes(): void
    {
        $compiled = (new StructureDefinitionCompiler())->compileExtension(self::definition([], [
            ['id' => 'Extension.extension', 'path' => 'Extension.extension', 'max' => '0'],
            ['id' => 'Extension.value[x]', 'path' => 'Extension.value[x]', 'max' => '1', 'type' => [['code' => 'string'], ['code' => 'Coding']]],
        ]));

        self::assertSame(['string', 'Coding'], $compiled->valueTypes);
        self::assertFalse($compiled->isComplex());
    }

    public function testAComplexExtensionHasItsSlices(): void
    {
        $compiled = (new StructureDefinitionCompiler())->compileExtension(self::definition([], [
            ['id' => 'Extension.extension:species', 'path' => 'Extension.extension', 'sliceName' => 'species', 'min' => 1, 'max' => '1'],
            ['id' => 'Extension.extension:species.value[x]', 'path' => 'Extension.extension.value[x]', 'type' => [['code' => 'CodeableConcept'], ['code' => 'string']]],
            ['id' => 'Extension.extension:tag', 'path' => 'Extension.extension', 'sliceName' => 'tag', 'min' => 1, 'max' => '*'],
            ['id' => 'Extension.extension:gone', 'path' => 'Extension.extension', 'sliceName' => 'gone', 'min' => 0, 'max' => '0'],
            ['id' => 'Extension.value[x]', 'path' => 'Extension.value[x]', 'max' => '0'],
        ]));

        self::assertSame([], $compiled->valueTypes);
        self::assertTrue($compiled->isComplex());
        self::assertEquals(
            [new ExtensionSlice('species', 1, '1', 'CodeableConcept'), new ExtensionSlice('tag', 1, '*', null), new ExtensionSlice('gone', 0, '0', null)],
            $compiled->subExtensions,
        );
        // A slice that may repeat is a list in the generated class, which does not require it
        self::assertSame([true, false, false], array_map(static fn (ExtensionSlice $s): bool => $s->isRequired(), $compiled->subExtensions));
    }

    /**
     * @param array<string, mixed>       $extra
     * @param list<array<string, mixed>> $elements
     *
     * @return array<string, mixed>
     */
    private static function definition(array $extra, array $elements): array
    {
        return [
            'url'        => self::URL,
            'type'       => 'Extension',
            'derivation' => 'constraint',
            'snapshot'   => ['element' => [['id' => 'Extension', 'path' => 'Extension'], ...$elements]],
            ...$extra,
        ];
    }
}
