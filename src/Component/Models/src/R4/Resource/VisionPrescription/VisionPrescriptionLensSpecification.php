<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\VisionPrescription;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\VisionEyesType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Contain the details of  the individual lens specifications and serves as the authorization for the fullfillment by certified professionals.
 */
#[FHIRBackboneElement(parentResource: 'VisionPrescription', elementPath: 'VisionPrescription.lensSpecification', fhirVersion: 'R4')]
class VisionPrescriptionLensSpecification extends BackboneElement
{
    public const FHIR_PROPERTY_MAP = [
        'id' => [
            'fhirType'          => 'http://hl7.org/fhirpath/System.String',
            'propertyKind'      => 'scalar',
            'isArray'           => false,
            'isRequired'        => false,
            'isChoice'          => false,
            'jsonKey'           => null,
            'phpType'           => null,
            'variants'          => null,
            'xmlSerializedName' => '@id',
        ],
        'extension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'extension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'modifierExtension' => [
            'fhirType'     => 'Extension',
            'propertyKind' => 'modifierExtension',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'product' => [
            'fhirType'     => 'CodeableConcept',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'eye' => [
            'fhirType'     => 'code',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => true,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'sphere' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'cylinder' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'axis' => [
            'fhirType'     => 'integer',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'prism' => [
            'fhirType'     => 'BackboneElement',
            'propertyKind' => 'backbone',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\Resource\VisionPrescription\VisionPrescriptionLensSpecificationPrism',
            'variants'     => null,
        ],
        'add' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'power' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'backCurve' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'diameter' => [
            'fhirType'     => 'decimal',
            'propertyKind' => 'scalar',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'duration' => [
            'fhirType'     => 'Quantity',
            'propertyKind' => 'complex',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'color' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'brand' => [
            'fhirType'     => 'string',
            'propertyKind' => 'primitive',
            'isArray'      => false,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => null,
            'variants'     => null,
        ],
        'note' => [
            'fhirType'     => 'Annotation',
            'propertyKind' => 'complex',
            'isArray'      => true,
            'isRequired'   => false,
            'isChoice'     => false,
            'jsonKey'      => null,
            'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation',
            'variants'     => null,
        ],
    ];

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
        /** @var CodeableConcept|null product Product to be supplied */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $product = null,
        /** @var VisionEyesType|null eye right | left */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?VisionEyesType $eye = null,
        /** @var numeric-string|null sphere Power of the lens */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $sphere = null,
        /** @var numeric-string|null cylinder Lens power for astigmatism */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $cylinder = null,
        /** @var int|null axis Lens meridian which contain no power for astigmatism */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $axis = null,
        /** @var array<VisionPrescriptionLensSpecificationPrism> prism Eye alignment compensation */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone', isArray: true)]
        public array $prism = [],
        /** @var numeric-string|null add Added power for multifocal levels */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $add = null,
        /** @var numeric-string|null power Contact lens power */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $power = null,
        /** @var numeric-string|null backCurve Contact lens back curvature */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $backCurve = null,
        /** @var numeric-string|null diameter Contact lens diameter */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $diameter = null,
        /** @var Quantity|null duration Lens wear duration */
        #[FhirProperty(fhirType: 'Quantity', propertyKind: 'complex')]
        public ?Quantity $duration = null,
        /** @var StringPrimitive|string|null color Color required */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $color = null,
        /** @var StringPrimitive|string|null brand Brand required */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $brand = null,
        /** @var array<Annotation> note Notes for coatings */
        #[FhirProperty(fhirType: 'Annotation', propertyKind: 'complex', isArray: true)]
        public array $note = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
