<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\CapabilityStatement;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\RestfulCapabilityModeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\MarkdownPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A definition of the restful capabilities of the solution, if any.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest', fhirVersion: 'R4B')]
class CapabilityStatementRest extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var RestfulCapabilityModeType|null mode client | server */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?RestfulCapabilityModeType $mode = null,
        /** @var MarkdownPrimitive|null documentation General description of implementation */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $documentation = null,
        /** @var CapabilityStatementRestSecurity|null security Information about security of implementation */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?CapabilityStatementRestSecurity $security = null,
        /** @var array<CapabilityStatementRestResource> resource Resource served on the REST interface */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\CapabilityStatement\CapabilityStatementRestResource',
        )]
        public array $resource = [],
        /** @var array<CapabilityStatementRestInteraction> interaction What operations are supported? */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\CapabilityStatement\CapabilityStatementRestInteraction',
        )]
        public array $interaction = [],
        /** @var array<CapabilityStatementRestResourceSearchParam> searchParam Search parameters for searching all resources */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\CapabilityStatement\CapabilityStatementRestResourceSearchParam',
        )]
        public array $searchParam = [],
        /** @var array<CapabilityStatementRestResourceOperation> operation Definition of a system level operation */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\CapabilityStatement\CapabilityStatementRestResourceOperation',
        )]
        public array $operation = [],
        /** @var array<CanonicalPrimitive> compartment Compartments served/used by system */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        public array $compartment = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
