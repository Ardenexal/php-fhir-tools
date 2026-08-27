<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Integration;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\ClinicalDocument;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use PHPUnit\Framework\TestCase;

/**
 * End-to-end regression guard for the generated CDA logical-model classes: a subclass must be
 * instantiable AND expose its inherited properties. Without each generated constructor forwarding
 * to parent::__construct(), inherited promoted properties are never initialised and reading them
 * throws "Typed property ... must not be accessed before initialization". Asserting on real,
 * committed generated classes is the only check that reproduces that original symptom — a
 * generator-unit assertion on the emitted source does not.
 *
 * @coversNothing
 */
final class CdaGeneratedClassInstantiationTest extends TestCase
{
    public function testCoreSubclassInitialisesInheritedProperties(): void
    {
        $ii = new II();

        // nullFlavor is inherited from ANY; uninitialised without parent::__construct forwarding.
        self::assertNull($ii->nullFlavor);
    }

    public function testAuSubclassExtendsCoreParentAndInitialisesInheritedProperties(): void
    {
        $document = new AuClinicalDocument();

        self::assertInstanceOf(ClinicalDocument::class, $document, 'AU specialization must extend its core parent');
        // classCode is inherited from ClinicalDocument with the fixed default 'DOCCLIN'.
        self::assertSame('DOCCLIN', $document->classCode);
    }
}
