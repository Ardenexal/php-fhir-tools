<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\Questionnaire\QuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding as R4BCoding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension as R4BExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\QuestionnaireItemTypeType as R4BQuestionnaireItemTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\QuestionnaireResponseStatusType as R4BQuestionnaireResponseStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive as R4BCanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive as R4BCodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive as R4BUriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UrlPrimitive as R4BUrlPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\Questionnaire\QuestionnaireItem as R4BQuestionnaireItem;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResource as R4BQuestionnaireResource;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResponse\QuestionnaireResponseItem as R4BQuestionnaireResponseItem;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResponse\QuestionnaireResponseItemAnswer as R4BQuestionnaireResponseItemAnswer;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\QuestionnaireResponseResource as R4BQuestionnaireResponseResource;
use Ardenexal\FHIRTools\Component\Validation\FHIRQuestionnaireValidator;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Fixture\InMemoryFHIRTerminologyClientFactory;
use PHPUnit\Framework\TestCase;

final class FHIRQuestionnaireValidatorPreferredServerTest extends TestCase
{
    private const string VS_URL        = 'http://hl7.org/fhir/ValueSet/answer-vs';

    private const string UNIT_VS_URL   = 'http://hl7.org/fhir/ValueSet/unit-vs';

    private const string SERVER_A      = 'http://tx-server-a.example.com/fhir';

    private const string SERVER_B      = 'http://tx-server-b.example.com/fhir';

    private const string SYSTEM        = 'http://loinc.org';

    private const string UCUM_SYSTEM   = 'http://unitsofmeasure.org';

    private const string EXT_PREFERRED = 'http://hl7.org/fhir/StructureDefinition/preferredTerminologyServer';

    private static function preferredServerExtension(string $url): Extension
    {
        return new Extension(url: self::EXT_PREFERRED, value: new UrlPrimitive(value: $url));
    }

    private static function codingAnswer(string $system, string $code): QuestionnaireResponseItemAnswer
    {
        return new QuestionnaireResponseItemAnswer(value: new Coding(
            system: new UriPrimitive(value: $system),
            code: new CodePrimitive(value: $code),
        ));
    }

    private static function completedResponse(QuestionnaireResponseItem ...$items): QuestionnaireResponseResource
    {
        return new QuestionnaireResponseResource(
            status: new QuestionnaireResponseStatusType('completed'),
            item: array_values($items),
        );
    }

    private static function quantityAnswer(string $system, string $code): QuestionnaireResponseItemAnswer
    {
        return new QuestionnaireResponseItemAnswer(value: new Quantity(
            value: '1.0',
            system: new UriPrimitive(value: $system),
            code: new CodePrimitive(value: $code),
        ));
    }

    private static function unitValueSetExtension(string $vsUrl): Extension
    {
        return new Extension(
            url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-unitValueSet',
            value: new CanonicalPrimitive(value: $vsUrl),
        );
    }

    // -------------------------------------------------------------------------
    // Factory = null: preferred-server extensions are silently ignored
    // -------------------------------------------------------------------------

    public function testFactoryNullIgnoresPreferredServerExtension(): void
    {
        // Item carries a preferred-server extension, but no factory → default client used.
        $default   = new InMemoryFHIRTerminologyClient([self::VS_URL => [self::SYSTEM . '|good' => true]], false);
        $validator = new FHIRQuestionnaireValidator(terminologyClient: $default);

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('choice'),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
            extension: [self::preferredServerExtension(self::SERVER_A)],
        )]);

        $response = self::completedResponse(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::codingAnswer(self::SYSTEM, 'good')],
        ));

        $report = $validator->validate($questionnaire, $response);
        self::assertEmpty($report->errors(), 'Default client permits the answer; no errors expected');
    }

    // -------------------------------------------------------------------------
    // Both null: no violations (backward-compat with M02)
    // -------------------------------------------------------------------------

    public function testClientAndFactoryBothNullProducesNoTerminologyViolations(): void
    {
        $validator = new FHIRQuestionnaireValidator();

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('choice'),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
        )]);

        $response = self::completedResponse(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::codingAnswer(self::SYSTEM, 'any-code')],
        ));

        $report = $validator->validate($questionnaire, $response);
        self::assertTrue($report->isValid());
    }

    // -------------------------------------------------------------------------
    // Item has preferredTerminologyServer → factory called with that URL
    // -------------------------------------------------------------------------

    public function testItemPreferredServerIsUsedInsteadOfDefault(): void
    {
        // SERVER_A denies the code; default client would permit it.
        $serverAClient = new InMemoryFHIRTerminologyClient([], false); // denies all
        $default       = new InMemoryFHIRTerminologyClient([self::VS_URL => [self::SYSTEM . '|code' => true]], true);
        $factory       = new InMemoryFHIRTerminologyClientFactory([self::SERVER_A => $serverAClient], $default);

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('choice'),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
            extension: [self::preferredServerExtension(self::SERVER_A)],
        )]);

        $response = self::completedResponse(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::codingAnswer(self::SYSTEM, 'code')],
        ));

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $default, clientFactory: $factory);
        $report    = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors(), 'Preferred server (SERVER_A) denies the code — error expected');
        self::assertStringContainsString('code', $report->errors()[0]->message);
    }

    // -------------------------------------------------------------------------
    // Nested item inherits ancestor's server when item has none
    // -------------------------------------------------------------------------

    public function testNestedItemInheritsAncestorPreferredServer(): void
    {
        // Parent has SERVER_A (denying); child has no preferred server.
        $serverAClient = new InMemoryFHIRTerminologyClient([], false);
        $default       = new InMemoryFHIRTerminologyClient([], true);
        $factory       = new InMemoryFHIRTerminologyClientFactory([self::SERVER_A => $serverAClient], $default);

        $child = new QuestionnaireItem(
            linkId: 'q1.1',
            type: new QuestionnaireItemTypeType('choice'),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
        );
        $parent = new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('group'),
            extension: [self::preferredServerExtension(self::SERVER_A)],
            item: [$child],
        );
        $questionnaire = new QuestionnaireResource(item: [$parent]);

        $childResponse = new QuestionnaireResponseItem(
            linkId: 'q1.1',
            answer: [self::codingAnswer(self::SYSTEM, 'code')],
        );
        $response = self::completedResponse(new QuestionnaireResponseItem(
            linkId: 'q1',
            item: [$childResponse],
        ));

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $default, clientFactory: $factory);
        $report    = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors(), 'Ancestor SERVER_A denies the code — error expected');
    }

    // -------------------------------------------------------------------------
    // Item server takes precedence over ancestor's server
    // -------------------------------------------------------------------------

    public function testItemServerOverridesAncestorServer(): void
    {
        // Ancestor has SERVER_B (permissive for the code); item has SERVER_A (denying).
        // SERVER_A is tried first → denial.
        $serverAClient = new InMemoryFHIRTerminologyClient([], false);
        $serverBClient = new InMemoryFHIRTerminologyClient([self::VS_URL => [self::SYSTEM . '|code' => true]], false);
        $default       = new InMemoryFHIRTerminologyClient([], false);
        $factory       = new InMemoryFHIRTerminologyClientFactory(
            [self::SERVER_A => $serverAClient, self::SERVER_B => $serverBClient],
            $default,
        );

        $child = new QuestionnaireItem(
            linkId: 'q1.1',
            type: new QuestionnaireItemTypeType('choice'),
            answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
            extension: [self::preferredServerExtension(self::SERVER_A)],
        );
        $parent = new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('group'),
            extension: [self::preferredServerExtension(self::SERVER_B)],
            item: [$child],
        );
        $questionnaire = new QuestionnaireResource(item: [$parent]);

        $childResponse = new QuestionnaireResponseItem(
            linkId: 'q1.1',
            answer: [self::codingAnswer(self::SYSTEM, 'code')],
        );
        $response = self::completedResponse(new QuestionnaireResponseItem(
            linkId: 'q1',
            item: [$childResponse],
        ));

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $default, clientFactory: $factory);
        $report    = $validator->validate($questionnaire, $response);

        // SERVER_A is the child's own preferred server and is tried first; it denies → error.
        // SERVER_B (ancestor) would have allowed the code, but is not reached (false is final).
        self::assertNotEmpty($report->errors(), 'Item SERVER_A is tried first and denies — error expected');
    }

    // -------------------------------------------------------------------------
    // Root Questionnaire preferred server used when no item/ancestor server
    // -------------------------------------------------------------------------

    public function testRootQuestionnairePreferredServerUsedWhenNoItemServer(): void
    {
        $serverAClient = new InMemoryFHIRTerminologyClient([], false);
        $default       = new InMemoryFHIRTerminologyClient([], true);
        $factory       = new InMemoryFHIRTerminologyClientFactory([self::SERVER_A => $serverAClient], $default);

        $questionnaire = new QuestionnaireResource(
            extension: [self::preferredServerExtension(self::SERVER_A)],
            item: [new QuestionnaireItem(
                linkId: 'q1',
                type: new QuestionnaireItemTypeType('choice'),
                answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
            )],
        );

        $response = self::completedResponse(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::codingAnswer(self::SYSTEM, 'code')],
        ));

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $default, clientFactory: $factory);
        $report    = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors(), 'Root questionnaire SERVER_A denies — error expected');
    }

    // -------------------------------------------------------------------------
    // Item preferred server takes precedence over root questionnaire server
    // -------------------------------------------------------------------------

    public function testItemServerTakesPrecedenceOverRootQuestionnaire(): void
    {
        // Root has SERVER_B (permissive); item has SERVER_A (denying). Item wins.
        $serverAClient = new InMemoryFHIRTerminologyClient([], false);
        $serverBClient = new InMemoryFHIRTerminologyClient([self::VS_URL => [self::SYSTEM . '|code' => true]], false);
        $default       = new InMemoryFHIRTerminologyClient([], false);
        $factory       = new InMemoryFHIRTerminologyClientFactory(
            [self::SERVER_A => $serverAClient, self::SERVER_B => $serverBClient],
            $default,
        );

        $questionnaire = new QuestionnaireResource(
            extension: [self::preferredServerExtension(self::SERVER_B)],
            item: [new QuestionnaireItem(
                linkId: 'q1',
                type: new QuestionnaireItemTypeType('choice'),
                answerValueSet: new CanonicalPrimitive(value: self::VS_URL),
                extension: [self::preferredServerExtension(self::SERVER_A)],
            )],
        );

        $response = self::completedResponse(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::codingAnswer(self::SYSTEM, 'code')],
        ));

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $default, clientFactory: $factory);
        $report    = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors(), 'Item SERVER_A is tried before root SERVER_B and denies — error expected');
    }

    // -------------------------------------------------------------------------
    // R4B: preferred server extension is resolved (cross-version coverage)
    // -------------------------------------------------------------------------

    public function testR4BItemPreferredServerIsUsed(): void
    {
        $serverAClient = new InMemoryFHIRTerminologyClient([], false);
        $default       = new InMemoryFHIRTerminologyClient([self::VS_URL => ['http://loinc.org|code' => true]], true);
        $factory       = new InMemoryFHIRTerminologyClientFactory([self::SERVER_A => $serverAClient], $default);

        $questionnaire = new R4BQuestionnaireResource(item: [new R4BQuestionnaireItem(
            linkId: 'q1',
            type: new R4BQuestionnaireItemTypeType('choice'),
            answerValueSet: new R4BCanonicalPrimitive(value: self::VS_URL),
            extension: [new R4BExtension(
                url: self::EXT_PREFERRED,
                value: new R4BUrlPrimitive(value: self::SERVER_A),
            )],
        )]);

        $response = new R4BQuestionnaireResponseResource(
            status: new R4BQuestionnaireResponseStatusType('completed'),
            item: [new R4BQuestionnaireResponseItem(
                linkId: 'q1',
                answer: [new R4BQuestionnaireResponseItemAnswer(value: new R4BCoding(
                    system: new R4BUriPrimitive(value: 'http://loinc.org'),
                    code: new R4BCodePrimitive(value: 'code'),
                ))],
            )],
        );

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $default, clientFactory: $factory);
        $report    = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors(), 'R4B: SERVER_A denies the code — error expected');
    }

    // -------------------------------------------------------------------------
    // Preferred server is used for checkUnitValueSetMembership (quantity type)
    // -------------------------------------------------------------------------

    public function testPreferredServerIsUsedForUnitValueSetMembership(): void
    {
        // SERVER_A denies the unit code; default would permit it.
        $serverAClient = new InMemoryFHIRTerminologyClient([], false);
        $default       = new InMemoryFHIRTerminologyClient([self::UNIT_VS_URL => [self::UCUM_SYSTEM . '|kg' => true]], true);
        $factory       = new InMemoryFHIRTerminologyClientFactory([self::SERVER_A => $serverAClient], $default);

        $questionnaire = new QuestionnaireResource(item: [new QuestionnaireItem(
            linkId: 'q1',
            type: new QuestionnaireItemTypeType('quantity'),
            extension: [
                self::unitValueSetExtension(self::UNIT_VS_URL),
                self::preferredServerExtension(self::SERVER_A),
            ],
        )]);

        $response = self::completedResponse(new QuestionnaireResponseItem(
            linkId: 'q1',
            answer: [self::quantityAnswer(self::UCUM_SYSTEM, 'kg')],
        ));

        $validator = new FHIRQuestionnaireValidator(terminologyClient: $default, clientFactory: $factory);
        $report    = $validator->validate($questionnaire, $response);

        self::assertNotEmpty($report->errors(), 'Preferred server (SERVER_A) denies the unit — error expected');
        self::assertStringContainsString('kg', $report->errors()[0]->message);
    }
}
