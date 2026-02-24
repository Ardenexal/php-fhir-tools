<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Unit\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;
use Ardenexal\FHIRTools\Component\FHIRPath\Exception\EvaluationException;
use Ardenexal\FHIRTools\Component\FHIRPath\Function\FunctionRegistry;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathLexer;
use Ardenexal\FHIRTools\Component\FHIRPath\Parser\FHIRPathParser;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the FHIR conformsTo() function.
 *
 * conformsTo(structure: String): Boolean
 *  - Delegates to a user-supplied callable for profile validation.
 *  - Returns [true]/[false] based on the callable's return value.
 *  - Returns [] when input is not exactly one item, or structure is not a non-empty string.
 *  - Throws EvaluationException when no validator callable is configured.
 *
 * @author FHIR Tools Contributors
 */
final class ConformsToFunctionTest extends TestCase
{
    private const PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/us-core-patient';

    private FHIRPathLexer $lexer;

    private FHIRPathParser $parser;

    private FHIRPathEvaluator $evaluator;

    protected function setUp(): void
    {
        $this->lexer     = new FHIRPathLexer();
        $this->parser    = new FHIRPathParser();
        $this->evaluator = new FHIRPathEvaluator();
    }

    protected function tearDown(): void
    {
        FunctionRegistry::reset();
    }

    private function evaluate(string $expression, mixed $resource): Collection
    {
        $tokens = $this->lexer->tokenize($expression);
        $ast    = $this->parser->parse($tokens);

        return $this->evaluator->evaluate($ast, $resource);
    }

    // -------------------------------------------------------------------------
    // Validator returns true / false
    // -------------------------------------------------------------------------

    public function testReturnsTrueWhenValidatorSaysTrue(): void
    {
        $this->evaluator->setConformsToValidator(fn (mixed $resource, string $url): bool => true);

        $resource = ['resourceType' => 'Patient', 'id' => 'example'];
        $result   = $this->evaluate(sprintf("conformsTo('%s')", self::PROFILE_URL), $resource);

        self::assertCount(1, $result->toArray());
        self::assertTrue($result->first());
    }

    public function testReturnsFalseWhenValidatorSaysFalse(): void
    {
        $this->evaluator->setConformsToValidator(fn (mixed $resource, string $url): bool => false);

        $resource = ['resourceType' => 'Patient', 'id' => 'example'];
        $result   = $this->evaluate(sprintf("conformsTo('%s')", self::PROFILE_URL), $resource);

        self::assertCount(1, $result->toArray());
        self::assertFalse($result->first());
    }

    // -------------------------------------------------------------------------
    // Callable receives the correct arguments
    // -------------------------------------------------------------------------

    public function testValidatorReceivesCorrectResourceAndUrl(): void
    {
        $capturedResource = null;
        $capturedUrl      = null;

        $this->evaluator->setConformsToValidator(
            function(mixed $resource, string $url) use (&$capturedResource, &$capturedUrl): bool {
                $capturedResource = $resource;
                $capturedUrl      = $url;

                return true;
            },
        );

        $resource = ['resourceType' => 'Patient', 'id' => 'p1'];
        $this->evaluate(sprintf("conformsTo('%s')", self::PROFILE_URL), $resource);

        self::assertSame($resource, $capturedResource);
        self::assertSame(self::PROFILE_URL, $capturedUrl);
    }

    public function testValidatorReceivesObjectResource(): void
    {
        $obj               = new \stdClass();
        $obj->resourceType = 'Observation';

        $capturedResource = null;
        $this->evaluator->setConformsToValidator(
            function(mixed $resource, string $url) use (&$capturedResource): bool {
                $capturedResource = $resource;

                return true;
            },
        );

        // Evaluate directly with the object as root resource
        $tokens = $this->lexer->tokenize(sprintf("conformsTo('%s')", self::PROFILE_URL));
        $ast    = $this->parser->parse($tokens);
        $this->evaluator->evaluate($ast, $obj);

        self::assertSame($obj, $capturedResource);
    }

    // -------------------------------------------------------------------------
    // Bad input → empty collection
    // -------------------------------------------------------------------------

    public function testEmptyInputReturnsEmpty(): void
    {
        $this->evaluator->setConformsToValidator(fn (mixed $r, string $u): bool => true);

        $result = $this->evaluate(sprintf("{}.conformsTo('%s')", self::PROFILE_URL), null);

        self::assertTrue($result->isEmpty());
    }

    public function testMultipleItemsInInputReturnsEmpty(): void
    {
        $this->evaluator->setConformsToValidator(fn (mixed $r, string $u): bool => true);

        // Build a collection with 2 items via union
        $resource = ['a' => 'val1', 'b' => 'val2'];
        $result   = $this->evaluate(sprintf("(a | b).conformsTo('%s')", self::PROFILE_URL), $resource);

        self::assertTrue($result->isEmpty());
    }

    public function testNonStringStructureParameterReturnsEmpty(): void
    {
        $this->evaluator->setConformsToValidator(fn (mixed $r, string $u): bool => true);

        // Pass an integer literal as the parameter instead of a string
        $resource = ['resourceType' => 'Patient'];
        $result   = $this->evaluate('conformsTo(42)', $resource);

        self::assertTrue($result->isEmpty());
    }

    // -------------------------------------------------------------------------
    // Missing configuration → EvaluationException
    // -------------------------------------------------------------------------

    public function testNoValidatorConfiguredThrows(): void
    {
        // No setConformsToValidator() call — evaluator has no callable configured

        $this->expectException(EvaluationException::class);
        $this->expectExceptionMessageMatches('/profile validator/i');

        $this->evaluate(sprintf("conformsTo('%s')", self::PROFILE_URL), ['resourceType' => 'Patient']);
    }

    // -------------------------------------------------------------------------
    // Profile URL is passed through intact (including special chars)
    // -------------------------------------------------------------------------

    public function testVersionedProfileUrlPassedToValidator(): void
    {
        $versionedUrl     = 'http://hl7.org/fhir/StructureDefinition/us-core-patient|3.1.1';
        $capturedUrl      = null;

        $this->evaluator->setConformsToValidator(
            function(mixed $r, string $url) use (&$capturedUrl): bool {
                $capturedUrl = $url;

                return true;
            },
        );

        $this->evaluate(sprintf("conformsTo('%s')", $versionedUrl), ['resourceType' => 'Patient']);

        self::assertSame($versionedUrl, $capturedUrl);
    }
}
