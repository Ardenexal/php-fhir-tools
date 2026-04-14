<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionCharacterization;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionCode;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionMoiety;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionMolecularWeight;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionName;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionProperty;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionRelationship;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionSourceMaterial;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionStructure;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubstanceDefinition
 *
 * @description The detailed description of a substance, typically at a level beyond what is used for prescribing.
 */
#[FhirResource(
    type: 'SubstanceDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/SubstanceDefinition',
    fhirVersion: 'R5',
)]
class SubstanceDefinitionResource extends DomainResourceResource
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
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
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
        /** @var array<Identifier> identifier Identifier by which this substance is known */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var StringPrimitive|string|null version A business level version identifier of the substance */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $version = null,
        /** @var CodeableConcept|null status Status of substance within the catalogue e.g. active, retired */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $status = null,
        /** @var array<CodeableConcept> classification A categorization, high level e.g. polymer or nucleic acid, or food, chemical, biological, or lower e.g. polymer linear or branch chain, or type of impurity */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $classification = [],
        /** @var CodeableConcept|null domain If the substance applies to human or veterinary use */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $domain = null,
        /** @var array<CodeableConcept> grade The quality standard, established benchmark, to which substance complies (e.g. USP/NF, BP) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $grade = [],
        /** @var MarkdownPrimitive|null description Textual description of the substance */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var array<Reference> informationSource Supporting literature */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $informationSource = [],
        /** @var array<Annotation> note Textual comment about the substance's catalogue or registry record */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Annotation',
        )]
        public array $note = [],
        /** @var array<Reference> manufacturer The entity that creates, makes, produces or fabricates the substance */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $manufacturer = [],
        /** @var array<Reference> supplier An entity that is the source for the substance. It may be different from the manufacturer */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $supplier = [],
        /** @var array<SubstanceDefinitionMoiety> moiety Moiety, for structural modifications */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionMoiety',
        )]
        public array $moiety = [],
        /** @var array<SubstanceDefinitionCharacterization> characterization General specifications for this substance */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionCharacterization',
        )]
        public array $characterization = [],
        /** @var array<SubstanceDefinitionProperty> property General specifications for this substance */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionProperty',
        )]
        public array $property = [],
        /** @var Reference|null referenceInformation General information detailing this substance */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $referenceInformation = null,
        /** @var array<SubstanceDefinitionMolecularWeight> molecularWeight The average mass of a molecule of a compound */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionMolecularWeight',
        )]
        public array $molecularWeight = [],
        /** @var SubstanceDefinitionStructure|null structure Structural information */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?SubstanceDefinitionStructure $structure = null,
        /** @var array<SubstanceDefinitionCode> code Codes associated with the substance */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionCode',
        )]
        public array $code = [],
        /** @var array<SubstanceDefinitionName> name Names applicable to this substance */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionName',
        )]
        public array $name = [],
        /** @var array<SubstanceDefinitionRelationship> relationship A link between this substance and another */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstanceDefinition\SubstanceDefinitionRelationship',
        )]
        public array $relationship = [],
        /** @var Reference|null nucleicAcid Data items specific to nucleic acids */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $nucleicAcid = null,
        /** @var Reference|null polymer Data items specific to polymers */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $polymer = null,
        /** @var Reference|null protein Data items specific to proteins */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $protein = null,
        /** @var SubstanceDefinitionSourceMaterial|null sourceMaterial Material or taxonomic/anatomical source */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?SubstanceDefinitionSourceMaterial $sourceMaterial = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
