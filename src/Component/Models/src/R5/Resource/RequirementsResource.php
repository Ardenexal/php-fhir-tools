<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Requirements\RequirementsStatement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Requirements
 *
 * @description A set of requirements - a list of features or behaviors of designed systems that are necessary to achieve organizational or regulatory goals.
 */
#[FhirResource(type: 'Requirements', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Requirements', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'cnl-0',
    severity: 'warning',
    expression: 'name.exists() implies name.matches(\'^[A-Z]([A-Za-z0-9_]){1,254}$\')',
    human: 'Name should be usable as an identifier for the module by machine processing applications such as code generation',
)]
class RequirementsResource extends AbstractDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRIsModifier(reason: 'This element is labeled as a modifier because the implicit rules may provide additional knowledge about the resource that modifies its meaning or interpretation')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages|5.0.0', strength: 'required')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<AbstractResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the resource that contains them')]
        public array $modifierExtension = [],
        /** @var UriPrimitive|null url Canonical identifier for this Requirements, represented as a URI (globally unique) */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $url = null,
        /** @var array<Identifier> identifier Additional identifier for the Requirements (business identifier) */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var StringPrimitive|string|null version Business version of the Requirements */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $version = null,
        /** @var StringPrimitive|string|Coding|null versionAlgorithm How to compare versions */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'string',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive',
                    'jsonKey'      => 'versionAlgorithmString',
                ],
                [
                    'fhirType'     => 'Coding',
                    'propertyKind' => 'complex',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
                    'jsonKey'      => 'versionAlgorithmCoding',
                ],
            ],
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/version-algorithm', strength: 'extensible')]
        public StringPrimitive|string|Coding|null $versionAlgorithm = null,
        /** @var StringPrimitive|string|null name Name for this Requirements (computer friendly) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null title Name for this Requirements (human friendly) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $title = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/publication-status|5.0.0', strength: 'required'), FHIRIsModifier(reason: 'This is labeled as "Is Modifier" because designs and applications should not use a retired Requirements without due consideration')]
        public ?PublicationStatusType $status = null,
        /** @var bool|null experimental For testing purposes, not real usage */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $experimental = null,
        /** @var DateTimePrimitive|null date Date last changed */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher/steward (organization or individual) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        #[FhirProperty(
            fhirType: 'ContactDetail',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\ContactDetail',
        )]
        public array $contact = [],
        /** @var MarkdownPrimitive|null description Natural language description of the requirements */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        #[FhirProperty(
            fhirType: 'UsageContext',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext',
        )]
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for Requirements (if applicable) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/jurisdiction', strength: 'extensible')]
        public array $jurisdiction = [],
        /** @var MarkdownPrimitive|null purpose Why this Requirements is defined */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $purpose = null,
        /** @var MarkdownPrimitive|null copyright Use and/or publishing restrictions */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $copyright = null,
        /** @var StringPrimitive|string|null copyrightLabel Copyright holder and year(s) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $copyrightLabel = null,
        /** @var array<CanonicalPrimitive> derivedFrom Other set of Requirements this builds on */
        #[FhirProperty(
            fhirType: 'canonical',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Requirements'])]
        public array $derivedFrom = [],
        /** @var array<UrlPrimitive> reference External artifact (rule/document etc. that) created this set of requirements */
        #[FhirProperty(
            fhirType: 'url',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UrlPrimitive',
        )]
        public array $reference = [],
        /** @var array<CanonicalPrimitive> actor Actor for these requirements */
        #[FhirProperty(
            fhirType: 'canonical',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/ActorDefinition'])]
        public array $actor = [],
        /** @var array<RequirementsStatement> statement Actual statement as markdown */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Requirements\RequirementsStatement',
        )]
        public array $statement = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
