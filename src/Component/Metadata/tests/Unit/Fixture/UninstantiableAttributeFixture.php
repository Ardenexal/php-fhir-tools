<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Fixture;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRStructureKindProvider;

/**
 * A class whose structural attribute cannot be instantiated.
 *
 * `MissingFixtureConstants` does not exist, and that is the point -- do not "fix" it by creating it.
 * Attribute arguments are evaluated when the attribute is instantiated, not when the file is parsed,
 * so this class loads normally and raises only when something calls `newInstance()` on its attribute.
 *
 * The hazard is real rather than contrived: the same lazy evaluation is why `self::CONST` inside an
 * attribute on an anonymous class fatals at read time rather than at compile time. A provider that
 * reads attributes to build an error message has to survive it, because the alternative is a crash
 * that destroys the conformance finding it was in the middle of reporting.
 *
 * @see FHIRStructureKindProvider
 */
#[FHIRComplexType(typeName: MissingFixtureConstants::TYPE_NAME)] // The class named here is deliberately absent -- see the docblock above.
final class UninstantiableAttributeFixture
{
}
