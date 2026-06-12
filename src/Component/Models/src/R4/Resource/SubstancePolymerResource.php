<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer\SubstancePolymerMonomerSet;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer\SubstancePolymerRepeat;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubstancePolymer
 *
 * @description Todo.
 */
#[FhirResource(
    type: 'SubstancePolymer',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/SubstancePolymer',
    fhirVersion: 'R4',
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
        /** @var CodeableConcept|null class Todo */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $class = null,
        /** @var CodeableConcept|null geometry Todo */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $geometry = null,
        /** @var array<CodeableConcept> copolymerConnectivity Todo */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
        )]
        public array $copolymerConnectivity = [],
        /** @var array<StringPrimitive|string> modification Todo */
        #[FhirProperty(
            fhirType: 'string',
            propertyKind: 'primitive',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive',
        )]
        public array $modification = [],
        /** @var array<SubstancePolymerMonomerSet> monomerSet Todo */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer\SubstancePolymerMonomerSet',
        )]
        public array $monomerSet = [],
        /** @var array<SubstancePolymerRepeat> repeat Todo */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer\SubstancePolymerRepeat',
        )]
        public array $repeat = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
