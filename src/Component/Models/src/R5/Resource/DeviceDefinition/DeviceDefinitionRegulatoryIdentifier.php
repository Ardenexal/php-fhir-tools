<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\DeviceDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\DeviceDefinitionRegulatoryIdentifierTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifier associated with the regulatory documentation (certificates, technical documentation, post-market surveillance documentation and reports) of a set of device models sharing the same intended purpose, risk class and essential design and manufacturing characteristics. One example is the Basic UDI-DI in Europe.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.regulatoryIdentifier', fhirVersion: 'R5')]
class DeviceDefinitionRegulatoryIdentifier extends BackboneElement
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
        /** @var DeviceDefinitionRegulatoryIdentifierTypeType|null type basic | master | license */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?DeviceDefinitionRegulatoryIdentifierTypeType $type = null,
        /** @var StringPrimitive|string|null deviceIdentifier The identifier itself */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $deviceIdentifier = null,
        /** @var UriPrimitive|null issuer The organization that issued this identifier */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?UriPrimitive $issuer = null,
        /** @var UriPrimitive|null jurisdiction The jurisdiction to which the deviceIdentifier applies */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?UriPrimitive $jurisdiction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
