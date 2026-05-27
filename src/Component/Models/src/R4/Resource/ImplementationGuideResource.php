<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
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
use Symfony\Component\Validator\Constraints\Count;
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
#[FHIRPathInvariant(
    key: 'ig-0',
    severity: 'warning',
    expression: 'name.matches(\'[A-Z]([A-Za-z0-9_]){0,254}\')',
    human: 'Name should be usable as an identifier for the module by machine processing applications such as code generation',
)]
#[FHIRPathInvariant(
    key: 'ig-2',
    severity: 'error',
    expression: 'definition.resource.fhirVersion.all(%context.fhirVersion contains $this)',
    human: 'If a resource has a fhirVersion, it must be oe of the versions defined for the Implementation Guide',
)]
class ImplementationGuideResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        #[FHIRValueSetBinding(
            valueSetUrl: 'http://hl7.org/fhir/ValueSet/languages',
            strength: 'preferred',
            maxValueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages',
        )]
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var UriPrimitive|null url Canonical identifier for this implementation guide, represented as a URI (globally unique) */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?UriPrimitive $url = null,
        /** @var StringPrimitive|string|null version Business version of the implementation guide */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|null name Name for this implementation guide (computer friendly) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this implementation guide (human friendly) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $title = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/publication-status|4.0.1', strength: 'required')]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $experimental = null,
        /** @var DateTimePrimitive|null date Date last changed */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        #[FhirProperty(
            fhirType: 'ContactDetail',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\ContactDetail',
        )]
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the implementation guide */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        #[FhirProperty(
            fhirType: 'UsageContext',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\UsageContext',
        )]
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for implementation guide (if applicable) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/jurisdiction', strength: 'extensible')]
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $copyright = null,
        /** @var IdPrimitive|null packageId NPM Package name for IG */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?IdPrimitive $packageId = null,
        /** @var SPDXLicenseType|null license SPDX license code for this IG (or not-open-source) */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/spdx-license|4.0.1', strength: 'required')]
        public ?SPDXLicenseType $license = null,
        /** @var array<FHIRVersionType> fhirVersion FHIR Version(s) this Implementation Guide targets */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true, isRequired: true), Count(min: 1), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/FHIR-version|4.0.1', strength: 'required')]
        public array $fhirVersion = [],
        /** @var array<ImplementationGuideDependsOn> dependsOn Another Implementation guide this depends on */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideDependsOn',
        )]
        public array $dependsOn = [],
        /** @var array<ImplementationGuideGlobal> global Profiles that apply globally */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\ImplementationGuide\ImplementationGuideGlobal',
        )]
        public array $global = [],
        /** @var ImplementationGuideDefinition|null definition Information needed to build the IG */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?ImplementationGuideDefinition $definition = null,
        /** @var ImplementationGuideManifest|null manifest Information about an assembled IG */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?ImplementationGuideManifest $manifest = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
