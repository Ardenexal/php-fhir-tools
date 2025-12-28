<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifier associated with the regulatory documentation (certificates, technical documentation, post-market surveillance documentation and reports) of a set of device models sharing the same intended purpose, risk class and essential design and manufacturing characteristics. One example is the Basic UDI-DI in Europe.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.regulatoryIdentifier', fhirVersion: 'R5')]
class FHIRDeviceDefinitionRegulatoryIdentifier extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRDeviceDefinitionRegulatoryIdentifierTypeType|null type basic | master | license */
        #[NotBlank]
        public ?FHIRDeviceDefinitionRegulatoryIdentifierTypeType $type = null,
        /** @var FHIRString|string|null deviceIdentifier The identifier itself */
        #[NotBlank]
        public FHIRString|string|null $deviceIdentifier = null,
        /** @var FHIRUri|null issuer The organization that issued this identifier */
        #[NotBlank]
        public ?FHIRUri $issuer = null,
        /** @var FHIRUri|null jurisdiction The jurisdiction to which the deviceIdentifier applies */
        #[NotBlank]
        public ?FHIRUri $jurisdiction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
