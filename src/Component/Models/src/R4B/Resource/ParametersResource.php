<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\Parameters\ParametersParameter;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Parameters
 *
 * @description This resource is a non-persisted resource used to pass information into and back from an [operation](operations.html). It has no other use, and there is no RESTful endpoint associated with it.
 */
#[FhirResource(type: 'Parameters', version: '4.3.0', url: 'http://hl7.org/fhir/StructureDefinition/Parameters', fhirVersion: 'R4B')]
class ParametersResource extends AbstractResource
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
        /** @var array<ParametersParameter> parameter Operation Parameter */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\Parameters\ParametersParameter',
        )]
        public array $parameter = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language);
    }
}
