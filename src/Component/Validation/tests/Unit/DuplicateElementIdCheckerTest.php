<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\ElementDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureDefinition\StructureDefinitionDifferential;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureDefinition\StructureDefinitionSnapshot;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\StructureDefinitionResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ElementDefinition as R5ElementDefinition;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\HumanName as R5HumanName;
use Ardenexal\FHIRTools\Component\Models\R5\Profile\SearchSetBundleProfile;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Bundle\BundleEntry as R5BundleEntry;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\PatientResource as R5PatientResource;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinition\StructureDefinitionDifferential as R5Differential;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinition\StructureDefinitionSnapshot as R5Snapshot;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\StructureDefinitionResource as R5StructureDefinitionResource;
use Ardenexal\FHIRTools\Component\Validation\DuplicateElementIdChecker;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use PHPUnit\Framework\TestCase;

/**
 * Shapes and wording from `R4.mni-patientOverview-bundle-example1b`, whose Java outcome reports
 * `Duplicate id value '2'` and `Duplicate id value '1a'`.
 */
final class DuplicateElementIdCheckerTest extends TestCase
{
    public function testRepeatedElementIdIsReportedAtTheSecondElement(): void
    {
        $violations = (new DuplicateElementIdChecker())->check(new PatientResource(
            identifier: [new Identifier(id: '2')],
            name: [new HumanName(id: '2')],
        ));

        self::assertSame(["Duplicate id value '2'"], $this->messages($violations));
        self::assertSame('name[0]', $violations[0]->path);
        self::assertSame('error', $violations[0]->severity);
    }

    public function testDistinctElementIdsAreAccepted(): void
    {
        self::assertSame([], (new DuplicateElementIdChecker())->check(new PatientResource(
            identifier: [new Identifier(id: 'a')],
            name: [new HumanName(id: 'b')],
        )));
    }

    /**
     * Bundle entries are Bundle elements, so their ids collide with each other; ids inside an entry's
     * resource belong to that resource, and a resource's own logical id is never an element id.
     */
    public function testEachResourceIsItsOwnScope(): void
    {
        $violations = (new DuplicateElementIdChecker())->check(new BundleResource(entry: [
            new BundleEntry(id: '1a', resource: new PatientResource(id: '1a', name: [new HumanName(id: 'x')])),
            new BundleEntry(resource: new PatientResource(id: '1a', name: [new HumanName(id: 'x')])),
            new BundleEntry(id: '1a'),
        ]));

        self::assertSame(["Duplicate id value '1a'"], $this->messages($violations));
        self::assertSame('entry[2]', $violations[0]->path);
    }

    /**
     * A StructureDefinition repeats every ElementDefinition id between snapshot and differential by
     * design; `R4.structureDefinition-11179-objectClass` reported four false duplicates without this.
     * R4 generates ElementDefinition as a backbone element, R5 as a complex type, so both are pinned.
     */
    public function testElementDefinitionIdsAreNotElementIds(): void
    {
        $checker = new DuplicateElementIdChecker();

        self::assertSame([], $checker->check(new StructureDefinitionResource(
            snapshot: new StructureDefinitionSnapshot(element: [new ElementDefinition(id: 'Patient.name')]),
            differential: new StructureDefinitionDifferential(element: [new ElementDefinition(id: 'Patient.name')]),
        )));

        self::assertSame([], $checker->check(new R5StructureDefinitionResource(
            snapshot: new R5Snapshot(element: [new R5ElementDefinition(id: 'Patient.name')]),
            differential: new R5Differential(element: [new R5ElementDefinition(id: 'Patient.name')]),
        )));
    }

    /**
     * A generated resource profile extends its resource class without redeclaring `#[FhirResource]`,
     * so the resource test reads the hierarchy: the profile's logical id is not an element id.
     */
    public function testResourceProfileIsStillAResource(): void
    {
        $bundle = new SearchSetBundleProfile(id: 'b1', entry: [new R5BundleEntry(id: 'b1')]);

        self::assertSame([], (new DuplicateElementIdChecker())->check($bundle));
    }

    /** The same instance placed twice serializes as two elements with one id, so it is a duplicate. */
    public function testTheSameInstanceTwiceIsADuplicate(): void
    {
        $name = new R5HumanName(id: 'x');

        $violations = (new DuplicateElementIdChecker())->check(new R5PatientResource(name: [$name, $name]));

        self::assertSame(["Duplicate id value 'x'"], $this->messages($violations));
        self::assertSame('name[1]', $violations[0]->path);
    }

    /**
     * @param list<FHIRValidationViolation> $violations
     *
     * @return list<string>
     */
    private function messages(array $violations): array
    {
        return array_map(static fn (FHIRValidationViolation $v): string => $v->message, $violations);
    }
}
