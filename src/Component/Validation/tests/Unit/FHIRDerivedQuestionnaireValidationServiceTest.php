<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRDerivedQuestionnaireValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRDerivedQuestionnaireValidator;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireResolverInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FHIRDerivedQuestionnaireValidationService::class)]
final class FHIRDerivedQuestionnaireValidationServiceTest extends TestCase
{
    private FHIRDerivedQuestionnaireValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new FHIRDerivedQuestionnaireValidator();
    }

    public function testReturnViolationsWhenBaseResolvedAndDerivedViolates(): void
    {
        $base = new QuestionnaireResource(
            url: new UriPrimitive(value: 'http://example.org/base'),
            item: [$this->item('a1', 'string')],
        );
        $derived = new QuestionnaireResource(
            derivedFrom: [new CanonicalPrimitive(value: 'http://example.org/base')],
            item: [$this->item('a1', 'integer')], // type mismatch — violation
        );

        $resolver = $this->createMock(FHIRQuestionnaireResolverInterface::class);
        $resolver->method('resolve')->with('http://example.org/base')->willReturn($base);

        $service = new FHIRDerivedQuestionnaireValidationService($this->validator, $resolver);
        $report  = $service->validate($derived);

        self::assertCount(1, $report->errors());
    }

    public function testReturnsEmptyReportWhenBaseUnresolvable(): void
    {
        $derived = new QuestionnaireResource(
            derivedFrom: [new CanonicalPrimitive(value: 'http://example.org/unknown')],
            item: [$this->item('a1', 'integer')],
        );

        $resolver = $this->createMock(FHIRQuestionnaireResolverInterface::class);
        $resolver->method('resolve')->willReturn(null);

        $service = new FHIRDerivedQuestionnaireValidationService($this->validator, $resolver);
        $report  = $service->validate($derived);

        self::assertCount(0, $report->violations);
    }

    public function testReturnsEmptyReportWhenNoDerivedFrom(): void
    {
        $derived = new QuestionnaireResource(
            derivedFrom: [],
            item: [$this->item('a1', 'string')],
        );

        $resolver = $this->createMock(FHIRQuestionnaireResolverInterface::class);
        $resolver->expects(self::never())->method('resolve');

        $service = new FHIRDerivedQuestionnaireValidationService($this->validator, $resolver);
        $report  = $service->validate($derived);

        self::assertCount(0, $report->violations);
    }

    public function testOnlyFirstDerivedFromUrlIsUsed(): void
    {
        $base = new QuestionnaireResource(
            url: new UriPrimitive(value: 'http://example.org/base'),
            item: [$this->item('a1', 'string')],
        );
        $derived = new QuestionnaireResource(
            derivedFrom: [
                new CanonicalPrimitive(value: 'http://example.org/base'),
                new CanonicalPrimitive(value: 'http://example.org/other'),
            ],
            item: [$this->item('a1', 'string')],
        );

        $resolver = $this->createMock(FHIRQuestionnaireResolverInterface::class);
        $resolver->expects(self::once())->method('resolve')
            ->with('http://example.org/base')
            ->willReturn($base);

        $service = new FHIRDerivedQuestionnaireValidationService($this->validator, $resolver);
        $report  = $service->validate($derived);

        self::assertCount(0, $report->errors());
    }

    public function testExtendsDerivationTypePermitsNewLinkIds(): void
    {
        $base = new QuestionnaireResource(
            url: new UriPrimitive(value: 'http://example.org/base'),
            item: [$this->item('a1', 'string')],
        );
        // 'new-item' is not in base — valid under 'extends', violation under 'compliesWith'
        $derived = new QuestionnaireResource(
            derivedFrom: [new CanonicalPrimitive(value: 'http://example.org/base')],
            item: [$this->item('a1', 'string'), $this->item('new-item', 'string')],
        );

        $resolver = $this->createMock(FHIRQuestionnaireResolverInterface::class);
        $resolver->method('resolve')->willReturn($base);

        $service = new FHIRDerivedQuestionnaireValidationService($this->validator, $resolver);
        $report  = $service->validate($derived, 'extends');

        self::assertCount(0, $report->errors());
    }

    public function testInspiredByDerivationTypeProducesNoViolations(): void
    {
        $base = new QuestionnaireResource(
            url: new UriPrimitive(value: 'http://example.org/base'),
            item: [$this->item('a1', 'string')],
        );
        // type mismatch and new linkId — both would violate under 'compliesWith'
        $derived = new QuestionnaireResource(
            derivedFrom: [new CanonicalPrimitive(value: 'http://example.org/base')],
            item: [$this->item('a1', 'integer'), $this->item('extra', 'string')],
        );

        $resolver = $this->createMock(FHIRQuestionnaireResolverInterface::class);
        $resolver->method('resolve')->willReturn($base);

        $service = new FHIRDerivedQuestionnaireValidationService($this->validator, $resolver);
        $report  = $service->validate($derived, 'inspiredBy');

        self::assertCount(0, $report->violations);
    }

    private function item(string $linkId, string $type): QuestionnaireItem
    {
        return new QuestionnaireItem(
            linkId: $linkId,
            type: new QuestionnaireItemTypeType($type),
        );
    }
}
