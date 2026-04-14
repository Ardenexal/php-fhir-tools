<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ValueSet;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A value set can also be "expanded", where the value set is turned into a simple collection of enumerated codes. This element holds the expansion, if it has been performed.
 */
#[FHIRBackboneElement(parentResource: 'ValueSet', elementPath: 'ValueSet.expansion', fhirVersion: 'R5')]
class ValueSetExpansion extends BackboneElement
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
        /** @var UriPrimitive|null identifier Identifies the value set expansion (business identifier) */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $identifier = null,
        /** @var UriPrimitive|null next Opaque urls for paging through expansion results */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $next = null,
        /** @var DateTimePrimitive|null timestamp Time ValueSet expansion happened */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?DateTimePrimitive $timestamp = null,
        /** @var int|null total Total number of codes in the expansion */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $total = null,
        /** @var int|null offset Offset at which this resource starts */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $offset = null,
        /** @var array<ValueSetExpansionParameter> parameter Parameter that controlled the expansion process */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ValueSet\ValueSetExpansionParameter',
        )]
        public array $parameter = [],
        /** @var array<ValueSetExpansionProperty> property Additional information supplied about each concept */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ValueSet\ValueSetExpansionProperty',
        )]
        public array $property = [],
        /** @var array<ValueSetExpansionContains> contains Codes in the value set */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ValueSet\ValueSetExpansionContains',
        )]
        public array $contains = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
