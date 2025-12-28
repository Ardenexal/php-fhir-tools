<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Unique device identifier (UDI) assigned to device label or package.  Note that the Device may include multiple udiCarriers as it either may include just the udiCarrier for the jurisdiction it is sold, or for multiple jurisdictions it could have been sold.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.udiDeviceIdentifier', fhirVersion: 'R4B')]
class FHIRDeviceDefinitionUdiDeviceIdentifier extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null deviceIdentifier The identifier that is to be associated with every Device that references this DeviceDefintiion for the issuer and jurisdication porvided in the DeviceDefinition.udiDeviceIdentifier */
        #[NotBlank]
        public \FHIRString|string|null $deviceIdentifier = null,
        /** @var FHIRUri|null issuer The organization that assigns the identifier algorithm */
        #[NotBlank]
        public ?\FHIRUri $issuer = null,
        /** @var FHIRUri|null jurisdiction The jurisdiction to which the deviceIdentifier applies */
        #[NotBlank]
        public ?\FHIRUri $jurisdiction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
