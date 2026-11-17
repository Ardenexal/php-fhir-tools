<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\ElementDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemOperatorType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItemEnableWhen;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ElementDefinition as R4BElementDefinition;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationServiceBuilder;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRPathInvariantErrata;
use PHPUnit\Framework\TestCase;

/**
 * The R4 invariants published wrongly are judged by their corrected expression.
 *
 * The published text is read off the generated R4 model rather than copied here, so a regenerated
 * model whose expression drifted from the errata key fails {@see testEveryCorrectionMatchesThePublishedR4Text()}
 * instead of silently disabling the correction.
 */
final class FHIRPathInvariantErrataTest extends TestCase
{
    public function testEveryCorrectionMatchesThePublishedR4Text(): void
    {
        $que12 = $this->publishedExpression(QuestionnaireItem::class, 'que-12');
        $eld19 = $this->publishedExpression(ElementDefinition::class, 'eld-19');

        self::assertSame('enableWhen.count() > 1 implies enableBehavior.exists()', FHIRPathInvariantErrata::correct($que12));
        self::assertSame($this->publishedExpression(R4BElementDefinition::class, 'eld-19'), FHIRPathInvariantErrata::correct($eld19));
    }

    public function testExpressionWithoutACorrectionIsReturnedUnchanged(): void
    {
        self::assertSame('name.exists()', FHIRPathInvariantErrata::correct('name.exists()'));
    }

    public function testTwoEnableWhenWithoutEnableBehaviorBreaksQue12(): void
    {
        // R4.q-enablewhen-me-wrong
        $item = new QuestionnaireItem(
            linkId: 'N2',
            type: new QuestionnaireItemTypeType(value: 'integer'),
            enableWhen: [
                new QuestionnaireItemEnableWhen(question: 'N0', operator: new QuestionnaireItemOperatorType(value: '='), answer: 1),
                new QuestionnaireItemEnableWhen(question: 'N1', operator: new QuestionnaireItemOperatorType(value: '='), answer: 1),
            ],
        );

        self::assertContains('que-12', $this->invariantKeys($item));
    }

    public function testOneEnableWhenStillPassesQue12(): void
    {
        $item = new QuestionnaireItem(
            linkId: 'N2',
            type: new QuestionnaireItemTypeType(value: 'integer'),
            enableWhen: [new QuestionnaireItemEnableWhen(question: 'N0', operator: new QuestionnaireItemOperatorType(value: '='), answer: 1)],
        );

        self::assertNotContains('que-12', $this->invariantKeys($item));
    }

    public function testPathWithAnEmptyNamePartBreaksEld19(): void
    {
        // R4.obs-unit-profile
        self::assertContains('eld-19', $this->invariantKeys(new ElementDefinition(path: 'Observation...unit')));
        self::assertNotContains('eld-19', $this->invariantKeys(new ElementDefinition(path: 'Observation.value[x]:valueQuantity.unit')));
    }

    /** @param class-string $class */
    private function publishedExpression(string $class, string $key): string
    {
        foreach ((new \ReflectionClass($class))->getAttributes() as $attribute) {
            $arguments = $attribute->getArguments();
            if (($arguments['key'] ?? null) === $key) {
                return (string) $arguments['expression'];
            }
        }

        self::fail(sprintf('%s declares no %s', $class, $key));
    }

    /** @return list<string> */
    private function invariantKeys(object $element): array
    {
        return array_values(array_filter(array_map(
            static fn (FHIRValidationViolation $v): ?string => $v->invariantKey,
            FHIRValidationServiceBuilder::create()->build()->validate($element)->errors(),
        )));
    }
}
