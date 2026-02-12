<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/TestScript
 * @description A structured set of tests against a FHIR server or client implementation to determine compliance against the FHIR specification.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'TestScript', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/TestScript', fhirVersion: 'R4')]
class TestScriptResource extends DomainResourceResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive url Canonical identifier for this test script, represented as a URI (globally unique) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier identifier Additional identifier for the test script */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string version Business version of the test script */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Name for this test script (computer friendly) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string title Name for this test script (human friendly) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $title = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType status draft | active | retired | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType $status = null,
		/** @var null|bool experimental For testing purposes, not real usage */
		public ?bool $experimental = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive date Date last changed */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $date = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string publisher Name of the publisher (organization or individual) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $publisher = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail> contact Contact details for the publisher */
		public array $contact = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive description Natural language description of the test script */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext> useContext The context that the content is intended to support */
		public array $useContext = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> jurisdiction Intended jurisdiction for test script (if applicable) */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive purpose Why this test script is defined */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive copyright Use and/or publishing restrictions */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $copyright = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptOrigin> origin An abstract server representing a client or sender in a message exchange */
		public array $origin = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptDestination> destination An abstract server representing a destination or receiver in a message exchange */
		public array $destination = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptMetadata metadata Required capability that is assumed to function correctly on the FHIR server being tested */
		public ?TestScript\TestScriptMetadata $metadata = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptFixture> fixture Fixture in the test script - by reference (uri) */
		public array $fixture = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> profile Reference of the validation profile */
		public array $profile = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptVariable> variable Placeholder for evaluated elements */
		public array $variable = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptSetup setup A series of required setup operations before tests are executed */
		public ?TestScript\TestScriptSetup $setup = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptTest> test A test in this script */
		public array $test = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptTeardown teardown A series of required clean up steps */
		public ?TestScript\TestScriptTeardown $teardown = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
