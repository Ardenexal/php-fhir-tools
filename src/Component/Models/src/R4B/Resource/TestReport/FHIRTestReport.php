<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/TestReport
 * @description A summary of information based on the results of executing a TestScript.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'TestReport', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/TestReport', fhirVersion: 'R4B')]
class FHIRTestReport extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier identifier External identifier */
		public ?FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string name Informal name of the executed TestScript */
		public FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTestReportStatusType status completed | in-progress | waiting | stopped | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRTestReportStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference testScript Reference to the  version-specific TestScript that was executed to produce this TestReport */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $testScript = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTestReportResultType result pass | fail | pending */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRTestReportResultType $result = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDecimal score The final score (percentage of tests passed) resulting from the execution of the TestScript */
		public ?FHIRDecimal $score = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string tester Name of the tester producing this report (Organization or individual) */
		public FHIRString|string|null $tester = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRDateTime issued When the TestScript was executed and this TestReport was generated */
		public ?FHIRDateTime $issued = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTestReportParticipant> participant A participant in the test execution, either the execution engine, a client, or a server */
		public array $participant = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTestReportSetup setup The results of the series of required setup operations before the tests were executed */
		public ?FHIRTestReportSetup $setup = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTestReportTest> test A test executed from the test script */
		public array $test = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRTestReportTeardown teardown The results of running the series of required clean up steps */
		public ?FHIRTestReportTeardown $teardown = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
