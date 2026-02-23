<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirProperty;
use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BiologicallyDerivedProductCategoryType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BiologicallyDerivedProductStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\BiologicallyDerivedProduct\BiologicallyDerivedProductCollection;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\BiologicallyDerivedProduct\BiologicallyDerivedProductManipulation;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\BiologicallyDerivedProduct\BiologicallyDerivedProductProcessing;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\BiologicallyDerivedProduct\BiologicallyDerivedProductStorage;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProduct
 *
 * @description A material substance originating from a biological entity intended to be transplanted or infused
 * into another (possibly the same) biological entity.
 */
#[FhirResource(
    type: 'BiologicallyDerivedProduct',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProduct',
    fhirVersion: 'R4B',
)]
class BiologicallyDerivedProductResource extends DomainResourceResource
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
        'meta' => [
            'fhirType'     => 'Meta',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'implicitRules' => [
            'fhirType'     => 'uri',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'language' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'text' => [
            'fhirType'     => 'Narrative',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'contained' => [
            'fhirType'     => 'Resource',
            'propertyKind' => 'resource',
            'isArray'      => true,
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
        'identifier' => [
            'fhirType'     => 'Identifier',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'productCategory' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'productCode' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'status' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'request' => [
            'fhirType'     => 'Reference',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'quantity' => [
            'fhirType'     => 'integer',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'parent' => [
            'fhirType'     => 'Reference',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'collection' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'processing' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'manipulation' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
        'storage' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'variants'     => null,
        ],
    ];

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
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?string $language = null,
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
        /** @var array<Identifier> identifier External ids for this item */
        #[FhirProperty(fhirType: 'Identifier', propertyKind: 'complex', isArray: true)]
        public array $identifier = [],
        /** @var BiologicallyDerivedProductCategoryType|null productCategory organ | tissue | fluid | cells | biologicalAgent */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?BiologicallyDerivedProductCategoryType $productCategory = null,
        /** @var CodeableConcept|null productCode What this biologically derived product is */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $productCode = null,
        /** @var BiologicallyDerivedProductStatusType|null status available | unavailable */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?BiologicallyDerivedProductStatusType $status = null,
        /** @var array<Reference> request Procedure request */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isArray: true)]
        public array $request = [],
        /** @var int|null quantity The amount of this biologically derived product */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $quantity = null,
        /** @var array<Reference> parent BiologicallyDerivedProduct parent */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex', isArray: true)]
        public array $parent = [],
        /** @var BiologicallyDerivedProductCollection|null collection How this product was collected */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?BiologicallyDerivedProductCollection $collection = null,
        /** @var array<BiologicallyDerivedProductProcessing> processing Any processing of the product during collection */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
        public array $processing = [],
        /** @var BiologicallyDerivedProductManipulation|null manipulation Any manipulation of product post-collection */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?BiologicallyDerivedProductManipulation $manipulation = null,
        /** @var array<BiologicallyDerivedProductStorage> storage Product storage */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
        public array $storage = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
