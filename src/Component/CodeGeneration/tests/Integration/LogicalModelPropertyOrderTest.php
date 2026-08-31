<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Integration;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuSubstanceAdministration;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\SubstanceAdministrationConsumable;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Guards the ordering metadata on the committed CDA classes.
 *
 * Two distinct things are checked, and both matter. Coverage: a class's `propertyOrder` must name
 * every property it can serialize, because a serializer sorting by this list has nowhere to put a
 * name the list omits. Position: the list must be in published order, because a complete but
 * unordered list passes coverage while still emitting invalid CDA.
 *
 * These assertions run against the real committed generated classes. A generator-unit assertion on
 * emitted source would not catch a regeneration that silently drops the list.
 *
 * @coversNothing
 */
final class LogicalModelPropertyOrderTest extends TestCase
{
    /**
     * Every generated CDA class paired with its declared content-model order.
     *
     * @return \Generator<string, array{class-string, LogicalModel}>
     */
    public static function cdaClassProvider(): \Generator
    {
        $modelsDir = dirname(__DIR__, 3) . '/CdaModels/src';
        $namespace = 'Ardenexal\\FHIRTools\\Component\\CdaModels\\';

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($modelsDir));
        foreach ($files as $file) {
            if (!$file instanceof \SplFileInfo || !$file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }
            $relative = substr($file->getPathname(), strlen($modelsDir) + 1);
            /** @var class-string $fqcn the generated classes mirror their namespace onto the directory tree, so the path maps to a real class name */
            $fqcn = $namespace . str_replace(['/', '.php'], ['\\', ''], $relative);
            if (!class_exists($fqcn)) {
                continue;
            }
            $attributes = (new \ReflectionClass($fqcn))->getAttributes(LogicalModel::class);
            if ($attributes === []) {
                continue;
            }

            yield (new \ReflectionClass($fqcn))->getShortName() => [$fqcn, $attributes[0]->newInstance()];
        }
    }

    /**
     * A serializer ordering by this list can only place names the list contains, so an omission is a
     * silently misplaced element rather than a cosmetic gap.
     *
     * @param class-string $fqcn
     */
    #[DataProvider('cdaClassProvider')]
    public function testEveryPublicPropertyIsListed(string $fqcn, LogicalModel $model): void
    {
        $declared = array_map(
            static fn (\ReflectionProperty $property): string => $property->getName(),
            (new \ReflectionClass($fqcn))->getProperties(\ReflectionProperty::IS_PUBLIC),
        );

        $missing = array_values(array_diff($declared, $model->propertyOrder));

        self::assertSame([], $missing, $fqcn . ' omits ' . implode(', ', $missing) . ' from propertyOrder');
    }

    /**
     * The published positions from the reported defect, stated as the CDA content model requires
     * them. Read from the class attribute rather than from a serialized document, so a failure points
     * at the generator instead of the XML writer.
     *
     * These are deliberately literal rather than re-derived from the package snapshots: the FHIR
     * package cache is a gitignored build artifact and is not present in every environment that runs
     * this suite. If a package pin moves and genuinely changes the content model, this test fails and
     * a human decides — which is the wanted behaviour for a conformance assertion.
     *
     * @param class-string $fqcn
     */
    #[DataProvider('publishedPositionProvider')]
    public function testReportedClassesCarryPublishedPositions(string $fqcn, string $name, ?string $expectedPrevious, ?string $expectedNext): void
    {
        $attributes = (new \ReflectionClass($fqcn))->getAttributes(LogicalModel::class);
        self::assertNotSame([], $attributes, $fqcn . ' carries no LogicalModel attribute');

        $order = $attributes[0]->newInstance()->propertyOrder;
        $index = array_search($name, $order, true);

        self::assertIsInt($index, $fqcn . ' does not list ' . $name);
        self::assertSame($expectedPrevious, $order[$index - 1] ?? null, $name . ' is preceded by the wrong element in ' . $fqcn);
        self::assertSame($expectedNext, $order[$index + 1] ?? null, $name . ' is followed by the wrong element in ' . $fqcn);
    }

    /**
     * @return \Generator<string, array{class-string, string, string|null, string|null}>
     */
    public static function publishedPositionProvider(): \Generator
    {
        // CDA puts InfrastructureRoot's realmCode/typeId/templateId first, so templateId follows
        // typeId and precedes the act's own leading element. This is the originally reported fault:
        // templateId serialized last on every act.
        yield 'act: templateId leads, ahead of the act\'s own elements' => [
            AuSubstanceAdministration::class, 'templateId', 'typeId', 'classCode',
        ];

        // The second reported fault. AU adds completionCode to ClinicalDocument, and the content
        // model places it between versionNumber and copyTime — not at the end, and not immediately
        // after confidentialityCode as the report's sample document suggested; that document simply
        // omitted languageCode, setId and versionNumber.
        yield 'document: completionCode sits between versionNumber and copyTime' => [
            AuClinicalDocument::class, 'completionCode', 'versionNumber', 'copyTime',
        ];

        // A synthesized nested wrapper. Its definition is built at generation time rather than read
        // from the package, so it exercises a separate path — and it extends InfrastructureRoot, so it
        // carries the same fault as the acts.
        yield 'wrapper: templateId leads a synthesized nested wrapper' => [
            SubstanceAdministrationConsumable::class, 'templateId', 'typeId', 'typeCode',
        ];
    }
}
