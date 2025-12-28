<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubstancePolymer
 *
 * @description Properties of a substance specific to it being a polymer.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'SubstancePolymer',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/SubstancePolymer',
    fhirVersion: 'R5',
)]
class FHIRSubstancePolymer extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier A business idenfier for this polymer, but typically this is handled by a SubstanceDefinition identifier */
        public ?\FHIRIdentifier $identifier = null,
        /** @var FHIRCodeableConcept|null class Overall type of the polymer */
        public ?\FHIRCodeableConcept $class = null,
        /** @var FHIRCodeableConcept|null geometry Polymer geometry, e.g. linear, branched, cross-linked, network or dendritic */
        public ?\FHIRCodeableConcept $geometry = null,
        /** @var array<FHIRCodeableConcept> copolymerConnectivity Descrtibes the copolymer sequence type (polymer connectivity) */
        public array $copolymerConnectivity = [],
        /** @var FHIRString|string|null modification Todo - this is intended to connect to a repeating full modification structure, also used by Protein and Nucleic Acid . String is just a placeholder */
        public \FHIRString|string|null $modification = null,
        /** @var array<FHIRSubstancePolymerMonomerSet> monomerSet Todo */
        public array $monomerSet = [],
        /** @var array<FHIRSubstancePolymerRepeat> repeat Specifies and quantifies the repeated units and their configuration */
        public array $repeat = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
