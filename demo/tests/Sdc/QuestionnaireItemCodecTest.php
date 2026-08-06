<?php

declare(strict_types=1);

namespace App\Tests\Sdc;

use App\Sdc\QuestionnaireItemCodec;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use PHPUnit\Framework\TestCase;

final class QuestionnaireItemCodecTest extends TestCase
{
    private function quantityItem(): array
    {
        return ['linkId' => 'weight', 'type' => 'quantity', 'text' => 'Weight'];
    }

    /** @return array<string, mixed> */
    private function answerValueSetItem(): array
    {
        return [
            'linkId'         => 'maritalStatus',
            'type'           => 'choice',
            'text'           => 'Marital status',
            'answerValueSet' => 'http://hl7.org/fhir/ValueSet/marital-status',
        ];
    }

    public function testQuantityValueAndUnitRoundTrip(): void
    {
        $codec = new QuestionnaireItemCodec();
        $value = $codec->fromFormValue($this->quantityItem(), '70.5 kg');

        self::assertInstanceOf(Quantity::class, $value);
        self::assertSame('70.5', $value->value); // raw numeric-string, not cast to float
        self::assertSame('kg', $value->unit);

        self::assertSame('70.5 kg', $codec->toFormValue($this->quantityItem(), $value));
    }

    public function testQuantityWithNoUnit(): void
    {
        $codec = new QuestionnaireItemCodec();
        $value = $codec->fromFormValue($this->quantityItem(), '42');

        self::assertInstanceOf(Quantity::class, $value);
        self::assertSame('42', $value->value);
        self::assertNull($value->unit);
        self::assertSame('42', $codec->toFormValue($this->quantityItem(), $value));
    }

    public function testQuantityWithoutLeadingNumberIsDropped(): void
    {
        $codec = new QuestionnaireItemCodec();
        self::assertNull($codec->fromFormValue($this->quantityItem(), 'kg'));
    }

    public function testQuantityEmptyFormValueIsUnanswered(): void
    {
        $codec = new QuestionnaireItemCodec();
        self::assertNull($codec->fromFormValue($this->quantityItem(), ''));
    }

    public function testAnswerValueSetFreeTextCodeProducesBareCoding(): void
    {
        $codec = new QuestionnaireItemCodec();
        $value = $codec->fromFormValue($this->answerValueSetItem(), 'M');

        self::assertInstanceOf(Coding::class, $value);
        self::assertSame('M', $value->code?->value);
        self::assertNull($value->system);
    }

    public function testAnswerValueSetCodingRoundTripsBackToTheFreeTextCode(): void
    {
        $codec = new QuestionnaireItemCodec();
        $value = $codec->fromFormValue($this->answerValueSetItem(), 'M');

        self::assertSame('M', $codec->toFormValue($this->answerValueSetItem(), $value));
    }

    public function testAnswerValueSetEmptyFormValueIsUnanswered(): void
    {
        $codec = new QuestionnaireItemCodec();
        self::assertNull($codec->fromFormValue($this->answerValueSetItem(), ''));
    }
}
