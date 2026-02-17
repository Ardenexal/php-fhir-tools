<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationCode;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationMoiety;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationName;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationProperty;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationRelationship;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationStructure;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification\SubstanceSpecificationStructureIsotopeMolecularWeight;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubstanceSpecification
 *
 * @description The detailed description of a substance, typically at a level beyond what is used for prescribing.
 */
#[FhirResource(
    type: 'SubstanceSpecification',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/SubstanceSpecification',
    fhirVersion: 'R4',
)]
class SubstanceSpecificationResource extends DomainResourceResource
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
        /** @var Identifier|null identifier Identifier by which this substance is known */
        public ?Identifier $identifier = null,
        /** @var CodeableConcept|null type High level categorization, e.g. polymer or nucleic acid */
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null status Status of substance within the catalogue e.g. approved */
        public ?CodeableConcept $status = null,
        /** @var CodeableConcept|null domain If the substance applies to only human or veterinary use */
        public ?CodeableConcept $domain = null,
        /** @var StringPrimitive|string|null description Textual description of the substance */
        public StringPrimitive|string|null $description = null,
        /** @var array<Reference> source Supporting literature */
        public array $source = [],
        /** @var StringPrimitive|string|null comment Textual comment about this record of a substance */
        public StringPrimitive|string|null $comment = null,
        /** @var array<SubstanceSpecificationMoiety> moiety Moiety, for structural modifications */
        public array $moiety = [],
        /** @var array<SubstanceSpecificationProperty> property General specifications for this substance, including how it is related to other substances */
        public array $property = [],
        /** @var Reference|null referenceInformation General information detailing this substance */
        public ?Reference $referenceInformation = null,
        /** @var SubstanceSpecificationStructure|null structure Structural information */
        public ?SubstanceSpecificationStructure $structure = null,
        /** @var array<SubstanceSpecificationCode> code Codes associated with the substance */
        public array $code = [],
        /** @var array<SubstanceSpecificationName> name Names applicable to this substance */
        public array $name = [],
        /** @var array<SubstanceSpecificationStructureIsotopeMolecularWeight> molecularWeight The molecular weight or weight range (for proteins, polymers or nucleic acids) */
        public array $molecularWeight = [],
        /** @var array<SubstanceSpecificationRelationship> relationship A link between this substance and another, with details of the relationship */
        public array $relationship = [],
        /** @var Reference|null nucleicAcid Data items specific to nucleic acids */
        public ?Reference $nucleicAcid = null,
        /** @var Reference|null polymer Data items specific to polymers */
        public ?Reference $polymer = null,
        /** @var Reference|null protein Data items specific to proteins */
        public ?Reference $protein = null,
        /** @var Reference|null sourceMaterial Material or taxonomic/anatomical source for the substance */
        public ?Reference $sourceMaterial = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
