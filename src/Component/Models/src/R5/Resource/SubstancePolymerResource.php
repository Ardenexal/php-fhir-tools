<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstancePolymer\SubstancePolymerMonomerSet;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstancePolymer\SubstancePolymerRepeat;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubstancePolymer
 *
 * @description Properties of a substance specific to it being a polymer.
 */
#[FhirResource(
    type: 'SubstancePolymer',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/SubstancePolymer',
    fhirVersion: 'R5',
)]
class SubstancePolymerResource extends AbstractDomainResource
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
        /** @var Identifier|null identifier A business idenfier for this polymer, but typically this is handled by a SubstanceDefinition identifier */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex')]
        public ?Identifier $identifier = null,
        /** @var CodeableConcept|null class Overall type of the polymer */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $class = null,
        /** @var CodeableConcept|null geometry Polymer geometry, e.g. linear, branched, cross-linked, network or dendritic */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $geometry = null,
        /** @var array<CodeableConcept> copolymerConnectivity Descrtibes the copolymer sequence type (polymer connectivity) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $copolymerConnectivity = [],
        /** @var StringPrimitive|string|null modification Todo - this is intended to connect to a repeating full modification structure, also used by Protein and Nucleic Acid . String is just a placeholder */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $modification = null,
        /** @var array<SubstancePolymerMonomerSet> monomerSet Todo */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstancePolymer\SubstancePolymerMonomerSet',
        )]
        public array $monomerSet = [],
        /** @var array<SubstancePolymerRepeat> repeat Specifies and quantifies the repeated units and their configuration */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\SubstancePolymer\SubstancePolymerRepeat',
        )]
        public array $repeat = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
