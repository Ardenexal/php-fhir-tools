<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Contract\CodingValidationResult;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyClientDecoratorInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRTerminologyMembershipClientInterface;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\NullFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\SliceDiscriminatorMatcher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class SliceDiscriminatorMatcherTest extends TestCase
{
    private SliceDiscriminatorMatcher $matcher;

    protected function setUp(): void
    {
        $this->matcher = new SliceDiscriminatorMatcher(PropertyAccess::createPropertyAccessor());
    }

    // ------------------------------------------------------------------
    // value discriminator
    // ------------------------------------------------------------------

    public function testValueMatchesExactString(): void
    {
        $item         = new \stdClass();
        $item->system = 'http://example.org';

        self::assertTrue($this->matcher->matches($item, 'value', 'system', 'http://example.org'));
    }

    public function testValueReturnsFalseForDifferentString(): void
    {
        $item         = new \stdClass();
        $item->system = 'http://other.org';

        self::assertFalse($this->matcher->matches($item, 'value', 'system', 'http://example.org'));
    }

    public function testValueReturnsFalseForNullAtPath(): void
    {
        $item         = new \stdClass();
        $item->system = null;

        self::assertFalse($this->matcher->matches($item, 'value', 'system', 'http://example.org'));
    }

    public function testValueMatchesStringableObject(): void
    {
        $primitive = new class ('http://example.org') implements \Stringable {
            public function __construct(private readonly string $value)
            {
            }

            public function __toString(): string
            {
                return $this->value;
            }
        };

        $item         = new \stdClass();
        $item->system = $primitive;

        self::assertTrue($this->matcher->matches($item, 'value', 'system', 'http://example.org'));
    }

    public function testValueMatchesNestedDotPath(): void
    {
        $item               = new \stdClass();
        $item->type         = new \stdClass();
        $item->type->system = 'http://example.org';

        self::assertTrue($this->matcher->matches($item, 'value', 'type.system', 'http://example.org'));
    }

    // ------------------------------------------------------------------
    // pattern discriminator
    // ------------------------------------------------------------------

    public function testPatternMatchesSubsetAtPath(): void
    {
        $coding = new class () implements \JsonSerializable {
            public function jsonSerialize(): mixed
            {
                return ['system' => 'http://example.org', 'code' => 'abc'];
            }
        };

        $item         = new \stdClass();
        $item->coding = $coding;

        self::assertTrue(
            $this->matcher->matches($item, 'pattern', 'coding', ['system' => 'http://example.org']),
        );
    }

    public function testPatternReturnsFalseForMissingPatternKey(): void
    {
        $coding = new class () implements \JsonSerializable {
            public function jsonSerialize(): mixed
            {
                return ['code' => 'abc'];
            }
        };

        $item         = new \stdClass();
        $item->coding = $coding;

        self::assertFalse(
            $this->matcher->matches($item, 'pattern', 'coding', ['system' => 'http://example.org']),
        );
    }

    public function testPatternWithScalarFallsBackToValueMatch(): void
    {
        $item         = new \stdClass();
        $item->system = 'http://example.org';

        self::assertTrue(
            $this->matcher->matches($item, 'pattern', 'system', 'http://example.org'),
        );
    }

    // ------------------------------------------------------------------
    // exists discriminator
    // ------------------------------------------------------------------

    public function testExistsTrueMatchesWhenPathPresent(): void
    {
        $item         = new \stdClass();
        $item->system = 'http://example.org';

        self::assertTrue($this->matcher->matches($item, 'exists', 'system', true));
    }

    public function testExistsFalseMatchesWhenPathAbsent(): void
    {
        $item = new \stdClass();

        self::assertTrue($this->matcher->matches($item, 'exists', 'missingField', false));
    }

    public function testExistsTrueReturnsFalseWhenPathAbsent(): void
    {
        $item = new \stdClass();

        self::assertFalse($this->matcher->matches($item, 'exists', 'missingField', true));
    }

    public function testExistsFalseReturnsFalseWhenPathPresent(): void
    {
        $item         = new \stdClass();
        $item->system = 'http://example.org';

        self::assertFalse($this->matcher->matches($item, 'exists', 'system', false));
    }

    /**
     * A slice whose definition states no presence carries no boolean, and must not read as
     * "absent": that made every item without the element match it.
     */
    public function testExistsWithNoPresenceMatchesNothing(): void
    {
        $item         = new \stdClass();
        $item->system = 'http://example.org';

        self::assertFalse($this->matcher->matches($item, 'exists', 'missingField', null));
        self::assertFalse($this->matcher->matches($item, 'exists', 'system', null));
    }

    // ------------------------------------------------------------------
    // unsupported discriminator types
    // ------------------------------------------------------------------

    /**
     * A discriminator type FHIR does not define warns and matches nothing.
     */
    public function testUnsupportedTypeEmitsWarningAndReturnsFalse(): void
    {
        $item    = new \stdClass();
        $warning = null;

        set_error_handler(static function(int $_errno, string $errstr) use (&$warning): bool {
            $warning = $errstr;

            return true;
        });

        $result = $this->matcher->matches($item, 'position', 'path', 'value');

        restore_error_handler();

        self::assertFalse($result);
        self::assertStringContainsString('not supported', $warning ?? '');
    }

    // ------------------------------------------------------------------
    // repeating elements along the discriminator path
    // ------------------------------------------------------------------

    /**
     * The vital-signs VSCat slice discriminates on 'coding.code' against a CodeableConcept whose
     * coding repeats. PropertyAccessor cannot read a property off a list, so before the path was
     * walked segment by segment this threw, was swallowed, and reported a required slice missing on
     * documents that satisfied it.
     */
    public function testValueMatchesThroughARepeatingElement(): void
    {
        $item = $this->codeableConceptWithCodes('vital-signs');

        self::assertTrue($this->matcher->matches($item, 'value', 'coding.code', 'vital-signs'));
    }

    public function testValueMatchesWhenOnlyOneOfSeveralRepetitionsCarriesTheValue(): void
    {
        $item = $this->codeableConceptWithCodes('survey', 'vital-signs', 'exam');

        self::assertTrue($this->matcher->matches($item, 'value', 'coding.code', 'vital-signs'));
    }

    public function testValueDoesNotMatchWhenNoRepetitionCarriesTheValue(): void
    {
        $item = $this->codeableConceptWithCodes('survey', 'exam');

        self::assertFalse($this->matcher->matches($item, 'value', 'coding.code', 'vital-signs'));
    }

    public function testValueDoesNotMatchWhenTheRepeatingElementIsEmpty(): void
    {
        $item = $this->codeableConceptWithCodes();

        self::assertFalse($this->matcher->matches($item, 'value', 'coding.code', 'vital-signs'));
    }

    public function testExistsFollowsRepeatingElements(): void
    {
        self::assertTrue(
            $this->matcher->matches($this->codeableConceptWithCodes('survey'), 'exists', 'coding.code', true),
        );
        self::assertTrue(
            $this->matcher->matches($this->codeableConceptWithCodes(), 'exists', 'coding.code', false),
        );
    }

    // ------------------------------------------------------------------
    // $this, composite, binding, type and profile discriminators
    // ------------------------------------------------------------------

    public function testThisIsTheItemItself(): void
    {
        $category = $this->codeableConceptWithCodes('social-history');
        $pattern  = ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/observation-category', 'code' => 'social-history']]];

        self::assertTrue($this->matcher->matches($category, 'pattern', '$this', $pattern));
        self::assertFalse($this->matcher->matches($this->codeableConceptWithCodes('exam'), 'pattern', '$this', $pattern));
    }

    /**
     * A fixed complex value on `$this` matches an item that holds every element it states.
     */
    public function testAComplexValueOnThisMatchesTheItem(): void
    {
        $coding = ['system' => 'http://terminology.hl7.org/CodeSystem/observation-category', 'code' => 'survey'];

        self::assertTrue($this->matcher->matches($this->codeableConceptWithCodes('survey')->coding[0], 'value', '$this', $coding));
        self::assertFalse($this->matcher->matches($this->codeableConceptWithCodes('exam')->coding[0], 'value', '$this', $coding));
    }

    /**
     * Every discriminator must match: a coding with the right code under the wrong system is not in
     * the slice, which matching on the first discriminator alone let through.
     */
    public function testACompositeDiscriminatorNeedsEveryPart(): void
    {
        $discriminators = [
            ['type' => 'value', 'path' => 'code', 'value' => 'survey'],
            ['type' => 'value', 'path' => 'system', 'value' => 'http://terminology.hl7.org/CodeSystem/observation-category'],
        ];
        $wrongSystem         = $this->codeableConceptWithCodes('survey')->coding[0];
        $wrongSystem->system = 'http://example.org/local';

        self::assertTrue($this->matcher->matchesAll($this->codeableConceptWithCodes('survey')->coding[0], $discriminators));
        self::assertFalse($this->matcher->matchesAll($wrongSystem, $discriminators));
    }

    public function testADiscriminatorWithNothingToMatchMatchesNoItem(): void
    {
        self::assertFalse($this->matcher->matchesAll($this->codeableConceptWithCodes('survey'), [['type' => 'pattern', 'path' => '$this']]));
        self::assertFalse($this->matcher->matchesAll($this->codeableConceptWithCodes('survey'), []));
    }

    /**
     * A binding discriminator asks the terminology client about each coding: a member matches, a
     * code outside the value set does not, and a server that gives no answer leaves it undecided.
     */
    public function testABindingDiscriminatorAsksTheTerminologyClient(): void
    {
        $client  = new class () implements FHIRTerminologyMembershipClientInterface {
            public function checkCode(string $valueSetUrl, mixed $value): ?bool
            {
                return null;
            }

            public function checkCoding(string $valueSetUrl, string $system, string $code): ?bool
            {
                return ['survey' => true, 'exam' => false][$code] ?? null;
            }

            public function validateCode(string $valueSetUrl, mixed $value): bool
            {
                return false;
            }

            public function validateCoding(string $valueSetUrl, string $system, string $code): bool
            {
                return false;
            }

            public function validateCodingWithDisplay(string $valueSetUrl, string $system, string $code, string $display): CodingValidationResult
            {
                return new CodingValidationResult(false);
            }
        };
        $matcher = new SliceDiscriminatorMatcher(PropertyAccess::createPropertyAccessor(), $client);
        $binding = [['type' => 'pattern', 'path' => '$this', 'valueSet' => 'http://example.org/ValueSet/categories']];

        self::assertTrue($matcher->matchesAll($this->codeableConceptWithCodes('survey')->coding[0], $binding));
        self::assertFalse($matcher->matchesAll($this->codeableConceptWithCodes('exam')->coding[0], $binding));
        self::assertNull($matcher->matchesAll($this->codeableConceptWithCodes('unknown')->coding[0], $binding));

        // A CodeableConcept belongs when any coding does; a code path is read with its coding's system
        self::assertTrue($matcher->matchesAll($this->codeableConceptWithCodes('exam', 'survey'), $binding));
        self::assertTrue($matcher->matchesAll($this->codeableConceptWithCodes('survey'), [['type' => 'value', 'path' => 'coding.code', 'valueSet' => 'http://example.org/ValueSet/categories']]));
        self::assertTrue($matcher->canAnswer($binding[0]));
    }

    /**
     * Without a terminology client, or with the null client that says yes to everything, a binding
     * discriminator is never answered.
     */
    public function testABindingDiscriminatorWithoutAClientIsUndecided(): void
    {
        $binding = ['type' => 'pattern', 'path' => '$this', 'valueSet' => 'http://example.org/ValueSet/categories'];
        $coding  = $this->codeableConceptWithCodes('survey')->coding[0];

        self::assertNull($this->matcher->matchesAll($coding, [$binding]));
        self::assertFalse($this->matcher->canAnswer($binding));

        $nullClient = new SliceDiscriminatorMatcher(PropertyAccess::createPropertyAccessor(), new NullFHIRTerminologyClient());
        self::assertNull($nullClient->matchesAll($coding, [$binding]));

        // The bundle wraps the null client in a cache when no server is configured
        $wrapped = new class (new NullFHIRTerminologyClient()) implements FHIRTerminologyClientDecoratorInterface {
            public function __construct(private readonly FHIRTerminologyClientInterface $inner)
            {
            }

            public function innerClient(): FHIRTerminologyClientInterface
            {
                return $this->inner;
            }

            public function validateCode(string $valueSetUrl, mixed $value): bool
            {
                return $this->inner->validateCode($valueSetUrl, $value);
            }

            public function validateCoding(string $valueSetUrl, string $system, string $code): bool
            {
                return $this->inner->validateCoding($valueSetUrl, $system, $code);
            }

            public function validateCodingWithDisplay(string $valueSetUrl, string $system, string $code, string $display): CodingValidationResult
            {
                return $this->inner->validateCodingWithDisplay($valueSetUrl, $system, $code, $display);
            }
        };
        $cached = new SliceDiscriminatorMatcher(PropertyAccess::createPropertyAccessor(), $wrapped);
        self::assertNull($cached->matchesAll($coding, [$binding]));
        self::assertFalse($cached->canAnswer($binding));

        $inMemory = new SliceDiscriminatorMatcher(PropertyAccess::createPropertyAccessor(), new InMemoryFHIRTerminologyClient([
            'http://example.org/ValueSet/categories' => ['http://terminology.hl7.org/CodeSystem/observation-category|survey' => true],
        ]));
        self::assertTrue($inMemory->matchesAll($coding, [$binding]));
    }

    public function testATypeDiscriminatorComparesTheFhirType(): void
    {
        $entry           = new \stdClass();
        $entry->resource = new PatientResource();

        self::assertTrue($this->matcher->matches($entry, 'type', 'resource', ['Patient']));
        self::assertTrue($this->matcher->matches($entry, 'type', 'resource', 'Patient'));
        self::assertFalse($this->matcher->matches($entry, 'type', 'resource', ['Observation']));
        self::assertFalse($this->matcher->matches($entry, 'type', 'resource', ['Resource']));

        // A plain string could be any string-like type, so it is neither in nor out
        $entry->resource = 'text';
        self::assertNull($this->matcher->matchesAll($entry, [['type' => 'type', 'path' => 'resource', 'value' => ['string']]]));
    }

    /**
     * A profile discriminator is answered by the profile check: its yes, no, and "cannot tell".
     * Without a check it is undecided.
     */
    public function testAProfileDiscriminatorAsksTheProfileCheck(): void
    {
        $entry           = new \stdClass();
        $entry->resource = new PatientResource();
        $discriminator   = ['type' => 'profile', 'path' => 'resource', 'value' => ['http://example.org/yes', 'http://example.org/unknown']];

        self::assertNull($this->matcher->matchesAll($entry, [$discriminator]));
        self::assertFalse($this->matcher->canAnswer($discriminator));

        $answers = ['http://example.org/yes' => true, 'http://example.org/no' => false];
        $checked = $this->matcher->withProfileCheck(static fn (object|array $value, string $url): ?bool => $answers[$url] ?? null);

        self::assertTrue($checked->matchesAll($entry, [$discriminator]));
        self::assertNull($checked->matchesAll($entry, [['type' => 'profile', 'path' => 'resource', 'value' => ['http://example.org/no', 'http://example.org/unknown']]]));
        self::assertFalse($checked->matchesAll($entry, [['type' => 'profile', 'path' => 'resource', 'value' => ['http://example.org/no']]]));
        self::assertTrue($checked->canAnswer($discriminator));

        // Nothing at the path conforms to anything
        self::assertFalse($checked->matchesAll(new \stdClass(), [$discriminator]));
    }

    /** Shaped like a CodeableConcept: a repeating `coding`, each with a `code`. */
    private function codeableConceptWithCodes(string ...$codes): \stdClass
    {
        $item         = new \stdClass();
        $item->coding = [];

        foreach ($codes as $code) {
            $coding         = new \stdClass();
            $coding->code   = $code;
            $coding->system = 'http://terminology.hl7.org/CodeSystem/observation-category';
            $item->coding[] = $coding;
        }

        return $item;
    }
}
