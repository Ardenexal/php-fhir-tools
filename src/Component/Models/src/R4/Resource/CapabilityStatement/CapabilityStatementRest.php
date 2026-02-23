<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RestfulCapabilityModeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A definition of the restful capabilities of the solution, if any.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest', fhirVersion: 'R4')]
class CapabilityStatementRest extends BackboneElement
{
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'     => 'http://hl7.org/fhirpath/System.String',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'modifierExtension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'modifierExtension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'mode' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'documentation' => [
            'fhirType'     => 'markdown',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'security' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'resource' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'interaction' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'searchParam' => [
            'fhirType'     => 'unknown',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'operation' => [
            'fhirType'     => 'unknown',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'compartment' => [
            'fhirType'     => 'canonical',
            'propertyKind' => 'primitive',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
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
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
        public array $resource = [],
        /** @var array<CapabilityStatementRestInteraction> interaction What operations are supported? */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
        public array $interaction = [],
        /** @var array<CapabilityStatementRestResourceSearchParam> searchParam Search parameters for searching all resources */
        #[FhirProperty(fhirType: 'unknown', propertyKind: 'complex', isArray: true)]
        public array $searchParam = [],
        /** @var array<CapabilityStatementRestResourceOperation> operation Definition of a system level operation */
        #[FhirProperty(fhirType: 'unknown', propertyKind: 'complex', isArray: true)]
        public array $operation = [],
        /** @var array<CanonicalPrimitive> compartment Compartments served/used by system */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isArray: true)]
        public array $compartment = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
