<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Oracle;

use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleHarnessCollaborators;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use PHPUnit\Framework\TestCase;

/**
 * Every optional collaborator of the comparison harness is either wired or declared absent.
 *
 * Three capabilities in the corpus-parity plan were sized against a pass that never ran, because an
 * absent collaborator does not fail — it reads as a capability gap. Nothing caught it any of the
 * three times. These tests are the thing that catches the fourth: they compare
 * OracleHarnessCollaborators::DECLARED_ABSENT against what OracleValidationServiceFactory actually
 * builds, in both directions, so neither a silent absence nor a stale declaration survives.
 *
 * @author Ardenexal
 */
class OracleHarnessCollaboratorsTest extends TestCase
{
    /**
     * The shortest a declared reason can be and still name a consequence.
     *
     * Every reason written so far runs to several sentences. The floor exists to reject a
     * placeholder — 'offline', 'TODO', 'not needed' — which would satisfy a set comparison while
     * leaving the next person to measure exactly as uninformed as if the row were missing.
     */
    private const int REASON_MIN_LENGTH = 80;

    /**
     * An absent collaborator that nobody declared fails here rather than in someone's measurement.
     *
     * This is the assertion the plan lacked. Wiring a real collaborator makes it fail too, which is
     * intended: the fix is to delete that row from DECLARED_ABSENT, and the failure is what prompts
     * it. Comparing sets in both directions is what stops a declaration from outliving its reason.
     */
    public function testAbsentCollaboratorsAreExactlyTheDeclaredOnes(): void
    {
        $declared = array_keys(OracleHarnessCollaborators::DECLARED_ABSENT);
        sort($declared);

        self::assertSame(
            $declared,
            OracleValidationServiceFactory::collaborators()->absent(),
            'A collaborator is absent without a declared reason, or declared absent while actually '
            . 'wired. Either way a capability measured against this harness is reading something '
            . 'other than what OracleHarnessCollaborators says it reads.',
        );
    }

    /**
     * A declared reason has to say what the absence costs, or it cannot do its one job.
     *
     * The row exists so the next person measuring an affected capability knows the number is
     * reading a disabled pass. A blank or placeholder reason passes a set comparison while telling
     * them nothing.
     */
    public function testEveryDeclaredAbsenceCarriesASubstantiveReason(): void
    {
        foreach (OracleHarnessCollaborators::DECLARED_ABSENT as $interface => $reason) {
            self::assertGreaterThan(
                self::REASON_MIN_LENGTH,
                strlen($reason),
                sprintf('%s is declared absent without explaining what the absence costs.', $interface),
            );
        }
    }

    /**
     * The IG type registry is wired by default, which is the M07 regression this pins.
     *
     * The harness passed no registry at all, and because FHIRValidationService gates its whole
     * extension pass on `$this->registry !== null`, the extension family measured a false zero.
     * Absent is legitimate only under the explicit resolveExtensions: false variant.
     */
    public function testTheIgTypeRegistryIsWiredUnlessExplicitlySwitchedOff(): void
    {
        self::assertNotContains(
            FHIRIGTypeRegistry::class,
            OracleValidationServiceFactory::collaborators()->absent(),
            'The IG type registry is absent in the default mode, which switches the extension pass '
            . 'off wholesale and makes every extension measurement read zero.',
        );

        self::assertContains(
            FHIRIGTypeRegistry::class,
            OracleValidationServiceFactory::collaborators(resolveExtensions: false)->absent(),
            'resolveExtensions: false is supposed to be the one mode that drops the registry.',
        );
    }

    /**
     * A new nullable collaborator on the validation service cannot slip in undeclared.
     *
     * OracleHarnessCollaborators names the three slots known today. This sweep reads the built
     * service instead, so a fourth optional collaborator added to FHIRValidationService's
     * constructor and left unfilled fails here without anyone having to remember this guard exists.
     */
    public function testTheBuiltServiceHoldsNoUndeclaredEmptyCollaborator(): void
    {
        $service    = OracleValidationServiceFactory::create(FhirVersion::R4);
        $properties = (new \ReflectionClass(FHIRValidationService::class))->getProperties();
        $declared   = OracleHarnessCollaborators::DECLARED_ABSENT;

        $undeclared = [];

        foreach ($properties as $property) {
            $slot = $property->getValue($service);

            if (($slot !== null && !is_object($slot)) || !OracleHarnessCollaborators::isAbsent($slot)) {
                continue;
            }

            $type = $property->getType();
            $name = $type instanceof \ReflectionNamedType ? $type->getName() : $property->getName();

            if (!array_key_exists($name, $declared)) {
                $undeclared[] = $property->getName();
            }
        }

        self::assertSame(
            [],
            $undeclared,
            'These FHIRValidationService slots are empty or hold a null object, and nothing '
            . 'declares why. Whatever pass reads one of them measures zero, silently — which is '
            . 'the failure mode OracleHarnessCollaborators exists to make loud.',
        );

        self::assertNotSame([], $properties, 'Reflection found no properties to sweep.');
    }
}
