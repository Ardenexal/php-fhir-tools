<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/TestReport
 * @description A summary of information based on the results of executing a TestScript.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'TestReport', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/TestReport', fhirVersion: 'R4')]
class TestReportResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier identifier External identifier */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Informal name of the executed TestScript */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\TestReportStatusType status completed | in-progress | waiting | stopped | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\TestReportStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference testScript Reference to the  version-specific TestScript that was executed to produce this TestReport */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $testScript = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\TestReportResultType result pass | fail | pending */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\TestReportResultType $result = null,
		/** @var null|float score The final score (percentage of tests passed) resulting from the execution of the TestScript */
		public ?float $score = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string tester Name of the tester producing this report (Organization or individual) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $tester = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive issued When the TestScript was executed and this TestReport was generated */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $issued = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport\TestReportParticipant> participant A participant in the test execution, either the execution engine, a client, or a server */
		public array $participant = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport\TestReportSetup setup The results of the series of required setup operations before the tests were executed */
		public ?TestReport\TestReportSetup $setup = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport\TestReportTest> test A test executed from the test script */
		public array $test = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport\TestReportTeardown teardown The results of running the series of required clean up steps */
		public ?TestReport\TestReportTeardown $teardown = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
