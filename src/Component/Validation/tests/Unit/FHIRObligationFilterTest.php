<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Validation\FHIRObligationContext;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\FilterContactPoint;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\FilteredObligationFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * An obligation with a FHIRPath filter covers only the occurrences of its element that the filter
 * selects (issue #70).
 */
final class FHIRObligationFilterTest extends TestCase
{
    private const string PLACER = 'http://example.org/actor/placer';

    public function testObligationIsMetWhenAnOccurrenceMatchesTheFilter(): void
    {
        $resource = new FilteredObligationFixture(telecom: [new FilterContactPoint('work'), new FilterContactPoint('home')]);

        self::assertSame([], $this->violationsAt('telecom', $resource));
    }

    public function testObligationFailsWhenNoOccurrenceMatchesTheFilter(): void
    {
        $resource = new FilteredObligationFixture(telecom: [new FilterContactPoint('work'), new FilterContactPoint('mobile')]);

        $violations = $this->violationsAt('telecom', $resource);

        self::assertCount(1, $violations);
        self::assertSame('error', $violations[0]->severity);
        self::assertSame(
            "Obligation SHALL:populate: property \"telecom\" must be populated with a value matching use = 'home'.",
            $violations[0]->message,
        );
    }

    public function testObligationFailsWhenTheElementIsAbsent(): void
    {
        self::assertCount(1, $this->violationsAt('telecom', new FilteredObligationFixture()));
    }

    public function testFilterIsEvaluatedOnASingleValuedElement(): void
    {
        $home = $this->violationsAt('primary', new FilteredObligationFixture(primary: new FilterContactPoint('home')));
        $work = $this->violationsAt('primary', new FilteredObligationFixture(primary: new FilterContactPoint('work')));

        self::assertSame([], $home);
        self::assertCount(1, $work);
        self::assertSame('warning', $work[0]->severity);
    }

    public function testNonBooleanFilterResultMatchesNothing(): void
    {
        self::assertCount(1, $this->violationsAt('nonBooleanFilter', new FilteredObligationFixture(nonBooleanFilter: [new FilterContactPoint('home')])));
    }

    public function testFilterThatCannotBeEvaluatedReportsInfoEvalError(): void
    {
        $violations = $this->violationsAt('brokenFilter', new FilteredObligationFixture(brokenFilter: [new FilterContactPoint('home')]));

        self::assertCount(1, $violations);
        self::assertSame('info', $violations[0]->severity);
        self::assertSame(FHIRViolationCode::EVAL_ERROR, $violations[0]->code);
        self::assertSame(
            'Obligation SHALL:populate on property "brokenFilter" not checked: its filter could not be evaluated: use = (',
            $violations[0]->message,
        );
    }

    public function testFilteredNoErrorSuppressesErrorsOnMatchingOccurrencesOnly(): void
    {
        $service  = new FHIRValidationService($this->validatorReportingErrorsAt(['noErrorHome[0]', 'noErrorHome[1]']), new FHIRPathService());
        $resource = new FilteredObligationFixture(noErrorHome: [new FilterContactPoint('work'), new FilterContactPoint('home')]);

        $report = $service->validate($resource, obligationContext: new FHIRObligationContext(self::PLACER));

        $errorPaths = array_map(static fn (FHIRValidationViolation $v): string => $v->path, $report->errors());
        self::assertContains('noErrorHome[0]', $errorPaths);
        self::assertNotContains('noErrorHome[1]', $errorPaths);
    }

    /**
     * @return list<FHIRValidationViolation>
     */
    private function violationsAt(string $path, FilteredObligationFixture $resource): array
    {
        $report = (new FHIRValidationService($this->validatorReportingErrorsAt([]), new FHIRPathService()))
            ->validate($resource, obligationContext: new FHIRObligationContext(self::PLACER));

        return array_values(array_filter(
            $report->violations,
            static fn (FHIRValidationViolation $v): bool => $v->path === $path,
        ));
    }

    /**
     * @param list<string> $paths
     */
    private function validatorReportingErrorsAt(array $paths): ValidatorInterface
    {
        $validator = $this->createStub(ValidatorInterface::class);
        $validator->method('validate')->willReturn(new ConstraintViolationList(array_map(
            static fn (string $path): ConstraintViolation => new ConstraintViolation(
                message: 'Cardinality failed.',
                messageTemplate: 'Cardinality failed.',
                parameters: [],
                root: new \stdClass(),
                propertyPath: $path,
                invalidValue: null,
                plural: null,
                code: FHIRViolationCode::ERROR,
                constraint: null,
            ),
            $paths,
        )));

        return $validator;
    }
}
