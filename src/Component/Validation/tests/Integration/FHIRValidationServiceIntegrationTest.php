<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\ComplexExtensionChecker;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Fixture\CountConstraintFixture;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Fixture\ProfileConstraintFixture;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileConstraintValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintValidatorFactoryInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

final class FHIRValidationServiceIntegrationTest extends TestCase
{
    private FHIRValidationService $service;

    protected function setUp(): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $defaultFactory   = new ConstraintValidatorFactory();

        $factory = new class ($propertyAccessor, $defaultFactory) implements ConstraintValidatorFactoryInterface {
            public function __construct(
                private readonly PropertyAccessorInterface $accessor,
                private readonly ConstraintValidatorFactory $default,
            ) {
            }

            public function getInstance(Constraint $constraint): ConstraintValidatorInterface
            {
                if ($constraint instanceof FHIRProfileConstraint) {
                    return new FHIRProfileConstraintValidator($this->accessor);
                }

                return $this->default->getInstance($constraint);
            }
        };

        $validator     = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->setConstraintValidatorFactory($factory)
            ->getValidator();
        $this->service = new FHIRValidationService($validator, new FHIRPathService());
    }

    /**
     * An incomplete complex extension is read as plain Extension, which declares no contexts. Its
     * placement is still checked against the typed class it could not become, so a misplaced one
     * reports both the missing slice and the wrong context.
     */
    public function testIncompleteComplexExtensionIsStillCheckedAgainstItsContext(): void
    {
        $json = '{"resourceType":"Observation","status":"final","code":{"text":"x"},'
            . '"extension":[{"url":"http://hl7.org/fhir/StructureDefinition/patient-animal",'
            . '"extension":[{"url":"breed","valueCodeableConcept":{"text":"Poodle"}}]}]}';

        $resource = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize($json);
        $report   = OracleValidationServiceFactory::create(FhirVersion::R4)->validate($resource);

        $classes = array_map(static fn ($v): string => $v->constraintClass, $report->errors());

        self::assertContains(ComplexExtensionChecker::class, $classes);
        self::assertContains(FHIRExtensionContext::class, $classes);
    }

    /**
     * `humanname-mothers-family` may only sit on `HumanName.family`. On the name itself the context
     * names a child of the element's own type, which the element cannot be, so it is denied — as the
     * reference validator denies it in `maiden-name-extension`.
     */
    public function testDataTypeRootedContextIsDeniedOnAnElementOfThatType(): void
    {
        $json = '{"resourceType":"Patient","name":[{"family":"Family","extension":[{'
            . '"url":"http://hl7.org/fhir/StructureDefinition/humanname-mothers-family","valueString":"Mothers family"}]}]}';

        $resource = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize($json);
        $report   = OracleValidationServiceFactory::create(FhirVersion::R4)->validate($resource);

        $messages = array_map(static fn ($v): string => $v->message, $report->errors());

        self::assertSame(
            ['Extension "http://hl7.org/fhir/StructureDefinition/humanname-mothers-family" is not permitted on element "Patient.name".'],
            $messages,
        );
    }

    /**
     * The same extension in the place its context names is permitted.
     */
    public function testDataTypeRootedContextIsPermittedOnTheElementItNames(): void
    {
        $json = '{"resourceType":"Patient","name":[{"family":"Family","_family":{"extension":[{'
            . '"url":"http://hl7.org/fhir/StructureDefinition/humanname-mothers-family","valueString":"Mothers family"}]}}]}';

        $resource = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize($json);
        $report   = OracleValidationServiceFactory::create(FhirVersion::R4)->validate($resource);

        self::assertSame([], $report->errors());
    }

    /**
     * The value-type check `japanese-utf8-ok` expects, on a document we can read. That case's three
     * findings stay unmatched only because its bytes are not UTF-8 and the reader refuses the file.
     */
    public function testExtensionValueOfADisallowedTypeIsReportedOnAHumanName(): void
    {
        $json = '{"resourceType":"Patient","name":[{"family":"Family","extension":[{'
            . '"url":"http://hl7.org/fhir/StructureDefinition/iso21090-EN-representation","valueString":"SYL"}]}]}';

        $resource = FHIRSerializationService::createDefault(FhirVersion::R4)->deserialize($json);
        $report   = OracleValidationServiceFactory::create(FhirVersion::R4)->validate($resource);

        $messages = array_map(static fn ($v): string => $v->message, $report->errors());

        self::assertContains(
            "The Extension 'http://hl7.org/fhir/StructureDefinition/iso21090-EN-representation' definition allows for the types [code] but found type string",
            $messages,
        );
    }

    public function testBuiltInCountViolationLandsInErrorBucket(): void
    {
        $fixture = new CountConstraintFixture(identifier: []);

        $report = $this->service->validate($fixture);

        self::assertFalse($report->isValid());
        self::assertCount(1, $report->errors());
        self::assertSame('identifier', $report->errors()[0]->path);
        self::assertCount(0, $report->warnings());
    }

    public function testBuiltInCountPassesWhenPropertyPopulated(): void
    {
        $fixture = new CountConstraintFixture(identifier: ['http://example.org']);

        $report = $this->service->validate($fixture);

        self::assertTrue($report->isValid());
        self::assertCount(0, $report->errors());
    }

    public function testProfileConstraintViolationNotRaisedWithoutProfileGroup(): void
    {
        $fixture = new ProfileConstraintFixture(identifier: []);

        $report = $this->service->validate($fixture); // no profile URL

        self::assertTrue($report->isValid(), 'Profile constraints must not fire without the profile group');
    }

    public function testProfileConstraintViolationRaisedWithProfileGroup(): void
    {
        $fixture = new ProfileConstraintFixture(identifier: []);

        $report = $this->service->validate($fixture, [ProfileConstraintFixture::PROFILE_URL]);

        self::assertFalse($report->isValid());
        self::assertCount(1, $report->errors());
        self::assertSame('identifier', $report->errors()[0]->path);
    }

    public function testProfileConstraintViolationCarriesProfileGroup(): void
    {
        $fixture = new ProfileConstraintFixture(identifier: []);

        $report = $this->service->validate($fixture, [ProfileConstraintFixture::PROFILE_URL]);

        self::assertSame(ProfileConstraintFixture::PROFILE_URL, $report->errors()[0]->profileGroup);
    }

    public function testProfileConstraintPassesWhenConstraintSatisfied(): void
    {
        $fixture = new ProfileConstraintFixture(identifier: ['http://example.org']);

        $report = $this->service->validate($fixture, [ProfileConstraintFixture::PROFILE_URL]);

        self::assertTrue($report->isValid());
    }
}
