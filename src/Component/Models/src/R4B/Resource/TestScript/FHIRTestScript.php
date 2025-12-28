<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/TestScript
 *
 * @description A structured set of tests against a FHIR server or client implementation to determine compliance against the FHIR specification.
 */
#[FhirResource(type: 'TestScript', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/TestScript', fhirVersion: 'R4B')]
class FHIRTestScript extends FHIRDomainResource
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
        /** @var FHIRUri|null url Canonical identifier for this test script, represented as a URI (globally unique) */
        #[NotBlank]
        public ?FHIRUri $url = null,
        /** @var FHIRIdentifier|null identifier Additional identifier for the test script */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRString|string|null version Business version of the test script */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|null name Name for this test script (computer friendly) */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this test script (human friendly) */
        public FHIRString|string|null $title = null,
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher (organization or individual) */
        public FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the test script */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for test script (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this test script is defined */
        public ?FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?FHIRMarkdown $copyright = null,
        /** @var array<FHIRTestScriptOrigin> origin An abstract server representing a client or sender in a message exchange */
        public array $origin = [],
        /** @var array<FHIRTestScriptDestination> destination An abstract server representing a destination or receiver in a message exchange */
        public array $destination = [],
        /** @var FHIRTestScriptMetadata|null metadata Required capability that is assumed to function correctly on the FHIR server being tested */
        public ?FHIRTestScriptMetadata $metadata = null,
        /** @var array<FHIRTestScriptFixture> fixture Fixture in the test script - by reference (uri) */
        public array $fixture = [],
        /** @var array<FHIRReference> profile Reference of the validation profile */
        public array $profile = [],
        /** @var array<FHIRTestScriptVariable> variable Placeholder for evaluated elements */
        public array $variable = [],
        /** @var FHIRTestScriptSetup|null setup A series of required setup operations before tests are executed */
        public ?FHIRTestScriptSetup $setup = null,
        /** @var array<FHIRTestScriptTest> test A test in this script */
        public array $test = [],
        /** @var FHIRTestScriptTeardown|null teardown A series of required clean up steps */
        public ?FHIRTestScriptTeardown $teardown = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
