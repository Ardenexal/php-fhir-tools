<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRVersionType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\SPDXLicenseType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideDependsOn;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideGlobal;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideManifest;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ImplementationGuide
 *
 * @description A set of rules of how a particular interoperability or standards problem is solved - typically through the use of FHIR resources. This resource is used to gather all the parts of an implementation guide into a logical whole and to publish a computable definition of all the parts.
 */
#[FhirResource(
    type: 'ImplementationGuide',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/ImplementationGuide',
    fhirVersion: 'R4',
)]
class ImplementationGuideResource extends DomainResourceResource
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
        /** @var UriPrimitive|null url Canonical identifier for this implementation guide, represented as a URI (globally unique) */
        #[NotBlank]
        public ?UriPrimitive $url = null,
        /** @var StringPrimitive|string|null version Business version of the implementation guide */
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this implementation guide (computer friendly) */
        #[NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this implementation guide (human friendly) */
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
        /** @var MarkdownPrimitive|null description Natural language description of the implementation guide */
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for implementation guide (if applicable) */
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        public ?MarkdownPrimitive $copyright = null,
        /** @var IdPrimitive|null packageId NPM Package name for IG */
        #[NotBlank]
        public ?IdPrimitive $packageId = null,
        /** @var SPDXLicenseType|null license SPDX license code for this IG (or not-open-source) */
        public ?SPDXLicenseType $license = null,
        /** @var array<FHIRVersionType> fhirVersion FHIR Version(s) this Implementation Guide targets */
        public array $fhirVersion = [],
        /** @var array<ImplementationGuideDependsOn> dependsOn Another Implementation guide this depends on */
        public array $dependsOn = [],
        /** @var array<ImplementationGuideGlobal> global Profiles that apply globally */
        public array $global = [],
        /** @var ImplementationGuideDefinition|null definition Information needed to build the IG */
        public ?ImplementationGuideDefinition $definition = null,
        /** @var ImplementationGuideManifest|null manifest Information about an assembled IG */
        public ?ImplementationGuideManifest $manifest = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
