<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTestReportResultType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRTestReportStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/TestReport
 *
 * @description A summary of information based on the results of executing a TestScript.
 */
#[FhirResource(type: 'TestReport', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/TestReport', fhirVersion: 'R4B')]
class FHIRTestReport extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier External identifier */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRString|string|null name Informal name of the executed TestScript */
        public FHIRString|string|null $name = null,
        /** @var FHIRTestReportStatusType|null status completed | in-progress | waiting | stopped | entered-in-error */
        #[NotBlank]
        public ?FHIRTestReportStatusType $status = null,
        /** @var FHIRReference|null testScript Reference to the  version-specific TestScript that was executed to produce this TestReport */
        #[NotBlank]
        public ?FHIRReference $testScript = null,
        /** @var FHIRTestReportResultType|null result pass | fail | pending */
        #[NotBlank]
        public ?FHIRTestReportResultType $result = null,
        /** @var FHIRDecimal|null score The final score (percentage of tests passed) resulting from the execution of the TestScript */
        public ?FHIRDecimal $score = null,
        /** @var FHIRString|string|null tester Name of the tester producing this report (Organization or individual) */
        public FHIRString|string|null $tester = null,
        /** @var FHIRDateTime|null issued When the TestScript was executed and this TestReport was generated */
        public ?FHIRDateTime $issued = null,
        /** @var array<FHIRTestReportParticipant> participant A participant in the test execution, either the execution engine, a client, or a server */
        public array $participant = [],
        /** @var FHIRTestReportSetup|null setup The results of the series of required setup operations before the tests were executed */
        public ?FHIRTestReportSetup $setup = null,
        /** @var array<FHIRTestReportTest> test A test executed from the test script */
        public array $test = [],
        /** @var FHIRTestReportTeardown|null teardown The results of running the series of required clean up steps */
        public ?FHIRTestReportTeardown $teardown = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
