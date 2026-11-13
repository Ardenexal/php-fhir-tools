<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Validator;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Validation\FHIRTerminologyClientInterface;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationMessageRegistry;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\HttpFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\InMemoryFHIRTerminologyClient;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRValueSetBindingValidator;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * A binding with no generated enum is answered by the terminology server, and the server is asked the
 * question a coding actually poses: is this system and code in the value set (issue #182).
 *
 * The value set URLs here deliberately resolve to no enum class, so every check goes to the client.
 *
 * @extends ConstraintValidatorTestCase<FHIRValueSetBindingValidator>
 */
final class FHIRValueSetBindingValidatorTerminologyCodingTest extends ConstraintValidatorTestCase
{
    use BindsValidatorContext;

    private const string VALUE_SET = 'https://healthterminologies.gov.au/fhir/ValueSet/pathology-procedure-1';

    private const string SNOMED = 'http://snomed.info/sct';

    private const string MEMBER = '26604007';

    private FHIRTerminologyClientInterface $client;

    protected function setUp(): void
    {
        $this->client = new InMemoryFHIRTerminologyClient([]);
        parent::setUp();
    }

    protected function createValidator(): FHIRValueSetBindingValidator
    {
        return new FHIRValueSetBindingValidator(new FHIRValidationMessageRegistry(), [], $this->client);
    }

    /**
     * Ontoserver answers 400 to `$validate-code` with a code and no system, member or not. The coding
     * carries its system, so the request must too.
     */
    public function testRequiredBindingSendsTheCodingsSystemToTheServer(): void
    {
        $this->useClient($this->ontoserver());

        $this->validator->validate($this->coding(self::MEMBER, self::SNOMED), new FHIRValueSetBinding(self::VALUE_SET, 'required'));

        $this->assertNoViolation();
    }

    public function testExtensibleBindingSendsTheCodingsSystemToTheServer(): void
    {
        $this->useClient($this->ontoserver());

        $this->validator->validate($this->concept([self::MEMBER, self::SNOMED]), new FHIRValueSetBinding(self::VALUE_SET, 'extensible'));

        $this->assertNoViolation();
    }

    /**
     * A `code` element carries no system. It reaches the client as a primitive wrapper, which the HTTP
     * client used to read as no code at all, and the server has to be told to infer the system.
     */
    public function testBareCodeElementIsAskedWithInferSystem(): void
    {
        $this->useClient($this->ontoserver());

        $this->validator->validate(new CodePrimitive(value: self::MEMBER), new FHIRValueSetBinding(self::VALUE_SET, 'required'));

        $this->assertNoViolation();
    }

    /** #170 runs a profile's bindings through this same path; an IG value set never has an enum. */
    public function testProfileBindingSendsTheCodingsSystemToTheServer(): void
    {
        $this->useClient($this->ontoserver());

        $this->validator->validate(
            $this->concept([self::MEMBER, self::SNOMED]),
            new FHIRValueSetBinding(self::VALUE_SET, 'required', fromProfile: true),
        );

        $this->assertNoViolation();
    }

    /**
     * A required binding on a CodeableConcept needs one coding from the value set; a translation or
     * local code beside it is allowed.
     */
    public function testConceptWithOneMemberCodingSatisfiesARequiredBinding(): void
    {
        $this->useClient($this->snomedMemberOnly());

        $value = $this->concept([self::MEMBER, self::SNOMED], ['LAB-123', 'http://lab.example.org/codes']);

        $this->validator->validate($value, new FHIRValueSetBinding(self::VALUE_SET, 'required'));

        $this->assertNoViolation();
    }

    public function testConceptWithNoMemberCodingIsReportedOnce(): void
    {
        $this->useClient($this->snomedMemberOnly());

        $value = $this->concept(['111111', self::SNOMED], ['LAB-123', 'http://lab.example.org/codes']);

        $this->validator->validate($value, new FHIRValueSetBinding(self::VALUE_SET, 'required'));

        self::assertCount(1, $this->context->getViolations());
    }

    /**
     * A server that refuses the request has not said the code is outside the value set, so the
     * binding is unchecked rather than failed.
     */
    public function testRefusedRequestIsUncheckedNotAMembershipFailure(): void
    {
        $this->useClient(new HttpFHIRTerminologyClient(
            new MockHttpClient(static fn (): MockResponse => new MockResponse('{"resourceType":"OperationOutcome"}', ['http_code' => 500])),
            'https://tx.example.org/fhir',
        ));

        $this->validator->validate($this->coding(self::MEMBER, self::SNOMED), new FHIRValueSetBinding(self::VALUE_SET, 'required'));

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_UNANSWERED_BINDING_MESSAGE)
            ->setParameters(['{{ url }}' => self::VALUE_SET])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }

    /** The unchecked finding names the value set that went unanswered, here the max value set. */
    public function testUnansweredMaxValueSetCheckNamesTheMaxValueSet(): void
    {
        $max = 'http://example.org/ValueSet/max';

        $this->useClient(new HttpFHIRTerminologyClient(
            new MockHttpClient(static function(string $method, string $url) use ($max): MockResponse {
                parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

                return ($query['url'] ?? null) === $max
                    ? new MockResponse('{"resourceType":"OperationOutcome"}', ['http_code' => 503])
                    : new MockResponse('{"resourceType":"Parameters","parameter":[{"name":"result","valueBoolean":true}]}');
            }),
            'https://tx.example.org/fhir',
        ));

        $this->validator->validate(
            $this->coding(self::MEMBER, self::SNOMED),
            new FHIRValueSetBinding(self::VALUE_SET, 'extensible', maxValueSetUrl: $max),
        );

        $this->buildViolation(FHIRValueSetBindingValidator::DEFAULT_UNANSWERED_BINDING_MESSAGE)
            ->setParameters(['{{ url }}' => $max])
            ->setCode(FHIRViolationCode::UNCHECKED_BINDING)
            ->assertRaised();
    }

    private function useClient(FHIRTerminologyClientInterface $client): void
    {
        $this->client    = $client;
        $this->validator = $this->createValidator();
        $this->bindContext($this->validator);
    }

    /**
     * Behaves like Ontoserver: a `$validate-code` with neither `system` nor `inferSystem=true` is a 400,
     * and SNOMED 26604007 is the only member.
     */
    private function ontoserver(): HttpFHIRTerminologyClient
    {
        $http = new MockHttpClient(static function(string $method, string $url): MockResponse {
            parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

            if (!isset($query['system']) && ($query['inferSystem'] ?? null) !== 'true') {
                return new MockResponse(
                    '{"resourceType":"OperationOutcome","issue":[{"severity":"error","code":"invalid","diagnostics":"$validate-code must supply a \'system\' or set \'inferSystem=true\'"}]}',
                    ['http_code' => 400],
                );
            }

            $member = ($query['system'] ?? self::SNOMED) === self::SNOMED && ($query['code'] ?? null) === self::MEMBER;

            return new MockResponse(
                (string) json_encode(['resourceType' => 'Parameters', 'parameter' => [['name' => 'result', 'valueBoolean' => $member]]]),
                ['http_code' => 200],
            );
        });

        return new HttpFHIRTerminologyClient($http, 'https://ontoserver.example.org/fhir');
    }

    private function snomedMemberOnly(): InMemoryFHIRTerminologyClient
    {
        return new InMemoryFHIRTerminologyClient([self::VALUE_SET => [self::SNOMED . '|' . self::MEMBER => true]], defaultResult: false);
    }

    private function coding(string $code, ?string $system = null): Coding
    {
        return new Coding(
            system: $system === null ? null : new UriPrimitive(value: $system),
            code: new CodePrimitive(value: $code),
        );
    }

    /**
     * @param array{0: string, 1: string|null} ...$codings [code, system] pairs
     */
    private function concept(array ...$codings): CodeableConcept
    {
        return new CodeableConcept(coding: array_map(fn (array $pair): Coding => $this->coding($pair[0], $pair[1]), $codings));
    }
}
