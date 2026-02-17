<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TestReportResultType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TestReportStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport\TestReportParticipant;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport\TestReportSetup;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport\TestReportTeardown;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport\TestReportTest;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/TestReport
 *
 * @description A summary of information based on the results of executing a TestScript.
 */
#[FhirResource(type: 'TestReport', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/TestReport', fhirVersion: 'R4')]
class TestReportResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var Identifier|null identifier External identifier */
        public ?Identifier $identifier = null,
        /** @var StringPrimitive|string|null name Informal name of the executed TestScript */
        public StringPrimitive|string|null $name = null,
        /** @var TestReportStatusType|null status completed | in-progress | waiting | stopped | entered-in-error */
        #[NotBlank]
        public ?TestReportStatusType $status = null,
        /** @var Reference|null testScript Reference to the  version-specific TestScript that was executed to produce this TestReport */
        #[NotBlank]
        public ?Reference $testScript = null,
        /** @var TestReportResultType|null result pass | fail | pending */
        #[NotBlank]
        public ?TestReportResultType $result = null,
        /** @var float|null score The final score (percentage of tests passed) resulting from the execution of the TestScript */
        public ?float $score = null,
        /** @var StringPrimitive|string|null tester Name of the tester producing this report (Organization or individual) */
        public StringPrimitive|string|null $tester = null,
        /** @var DateTimePrimitive|null issued When the TestScript was executed and this TestReport was generated */
        public ?DateTimePrimitive $issued = null,
        /** @var array<TestReportParticipant> participant A participant in the test execution, either the execution engine, a client, or a server */
        public array $participant = [],
        /** @var TestReportSetup|null setup The results of the series of required setup operations before the tests were executed */
        public ?TestReportSetup $setup = null,
        /** @var array<TestReportTest> test A test executed from the test script */
        public array $test = [],
        /** @var TestReportTeardown|null teardown The results of running the series of required clean up steps */
        public ?TestReportTeardown $teardown = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
