<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptDestination;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptFixture;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptMetadata;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptOrigin;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptSetup;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptTeardown;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptTest;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript\TestScriptVariable;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/TestScript
 *
 * @description A structured set of tests against a FHIR server or client implementation to determine compliance against the FHIR specification.
 */
#[FhirResource(type: 'TestScript', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/TestScript', fhirVersion: 'R4')]
class TestScriptResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Canonical identifier for this test script, represented as a URI (globally unique) */
        #[NotBlank]
        public ?UriPrimitive $url = null,
        /** @var Identifier|null identifier Additional identifier for the test script */
        public ?Identifier $identifier = null,
        /** @var StringPrimitive|string|null version Business version of the test script */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this test script (computer friendly) */
        #[NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this test script (human friendly) */
        public StringPrimitive|string|null $title = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        public ?bool $experimental = null,
        /** @var DateTimePrimitive|null date Date last changed */
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the test script */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for test script (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this test script is defined */
        public ?MarkdownPrimitive $purpose = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var array<TestScriptOrigin> origin An abstract server representing a client or sender in a message exchange */
        public array $origin = [],
        /** @var array<TestScriptDestination> destination An abstract server representing a destination or receiver in a message exchange */
        public array $destination = [],
        /** @var TestScriptMetadata|null metadata Required capability that is assumed to function correctly on the FHIR server being tested */
        public ?TestScriptMetadata $metadata = null,
        /** @var array<TestScriptFixture> fixture Fixture in the test script - by reference (uri) */
        public array $fixture = [],
        /** @var array<Reference> profile Reference of the validation profile */
        public array $profile = [],
        /** @var array<TestScriptVariable> variable Placeholder for evaluated elements */
        public array $variable = [],
        /** @var TestScriptSetup|null setup A series of required setup operations before tests are executed */
        public ?TestScriptSetup $setup = null,
        /** @var array<TestScriptTest> test A test in this script */
        public array $test = [],
        /** @var TestScriptTeardown|null teardown A series of required clean up steps */
        public ?TestScriptTeardown $teardown = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
