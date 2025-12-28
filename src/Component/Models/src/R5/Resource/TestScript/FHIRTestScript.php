<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/TestScript
 *
 * @description A structured set of tests against a FHIR server or client implementation to determine compliance against the FHIR specification.
 */
#[FhirResource(type: 'TestScript', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/TestScript', fhirVersion: 'R5')]
class FHIRTestScript extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this test script, represented as a URI (globally unique) */
        public ?FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the test script */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the test script */
        public FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public FHIRString|string|FHIRCoding|null $versionAlgorithmX = null,
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
        /** @var FHIRString|string|null publisher Name of the publisher/steward (organization or individual) */
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
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public FHIRString|string|null $copyrightLabel = null,
        /** @var array<FHIRTestScriptOrigin> origin An abstract server representing a client or sender in a message exchange */
        public array $origin = [],
        /** @var array<FHIRTestScriptDestination> destination An abstract server representing a destination or receiver in a message exchange */
        public array $destination = [],
        /** @var FHIRTestScriptMetadata|null metadata Required capability that is assumed to function correctly on the FHIR server being tested */
        public ?FHIRTestScriptMetadata $metadata = null,
        /** @var array<FHIRTestScriptScope> scope Indication of the artifact(s) that are tested by this test case */
        public array $scope = [],
        /** @var array<FHIRTestScriptFixture> fixture Fixture in the test script - by reference (uri) */
        public array $fixture = [],
        /** @var array<FHIRCanonical> profile Reference of the validation profile */
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
