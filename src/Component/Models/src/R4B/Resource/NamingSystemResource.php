<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactDetail;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\NamingSystemTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\PublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\NamingSystem\NamingSystemUniqueId;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Vocabulary)
 *
 * @see http://hl7.org/fhir/StructureDefinition/NamingSystem
 *
 * @description A curated namespace that issues unique symbols within that namespace for the identification of concepts, people, devices, etc.  Represents a "System" used within the Identifier and Coding data types.
 */
#[FhirResource(type: 'NamingSystem', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/NamingSystem', fhirVersion: 'R4B')]
#[FHIRPathInvariant(
    key: 'nsd-0',
    severity: 'warning',
    expression: 'name.exists() implies name.matches(\'[A-Z]([A-Za-z0-9_]){0,254}\')',
    human: 'Name should be usable as an identifier for the module by machine processing applications such as code generation',
)]
#[FHIRPathInvariant(
    key: 'nsd-1',
    severity: 'error',
    expression: 'kind != \'root\' or uniqueId.all(type != \'uuid\')',
    human: 'Root systems cannot have uuid identifiers',
)]
#[FHIRPathInvariant(
    key: 'nsd-2',
    severity: 'error',
    expression: 'uniqueId.where(preferred = true).select(type).isDistinct()',
    human: 'Can\'t have more than one preferred identifier for a type',
)]
class NamingSystemResource extends AbstractDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRIsModifier(reason: 'This element is labeled as a modifier because the implicit rules may provide additional knowledge about the resource that modifies it\'s meaning or interpretation')]
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
        /** @var array<AbstractResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the resource that contains them')]
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null name Name for this naming system (computer friendly) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var PublicationStatusType|null status draft | active | retired | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/publication-status|4.3.0', strength: 'required'), FHIRIsModifier(reason: 'This is labeled as "Is Modifier" because applications should not use a retired {{title}} without due consideration')]
        public ?PublicationStatusType $status = null,
        /** @var NamingSystemTypeType|null kind codesystem | identifier | root */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/namingsystem-type|4.3.0', strength: 'required')]
        public ?NamingSystemTypeType $kind = null,
        /** @var DateTimePrimitive|null date Date last changed */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?DateTimePrimitive $date = null,
        /** @var StringPrimitive|string|null publisher Name of the publisher (organization or individual) */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $publisher = null,
        /** @var array<ContactDetail> contact Contact details for the publisher */
        #[FhirProperty(
            fhirType: 'ContactDetail',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\ContactDetail',
        )]
        public array $contact = [],
        /** @var StringPrimitive|string|null responsible Who maintains system namespace? */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $responsible = null,
        /** @var CodeableConcept|null type e.g. driver,  provider,  patient, bank etc. */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/identifier-type', strength: 'extensible')]
        public ?CodeableConcept $type = null,
        /** @var MarkdownPrimitive|null description Natural language description of the naming system */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var array<UsageContext> useContext The context that the content is intended to support */
        #[FhirProperty(
            fhirType: 'UsageContext',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\UsageContext',
        )]
        public array $useContext = [],
        /** @var array<CodeableConcept> jurisdiction Intended jurisdiction for naming system (if applicable) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/jurisdiction', strength: 'extensible')]
        public array $jurisdiction = [],
        /** @var StringPrimitive|string|null usage How/where is it used */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $usage = null,
        /** @var array<NamingSystemUniqueId> uniqueId Unique identifiers used for system */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            isRequired: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\NamingSystem\NamingSystemUniqueId',
        )]
        #[Count(min: 1)]
        public array $uniqueId = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
