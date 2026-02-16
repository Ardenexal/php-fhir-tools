<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\DeviceDefinition;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Unique device identifier (UDI) assigned to device label or package.  Note that the Device may include multiple udiCarriers as it either may include just the udiCarrier for the jurisdiction it is sold, or for multiple jurisdictions it could have been sold.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.udiDeviceIdentifier', fhirVersion: 'R4')]
class DeviceDefinitionUdiDeviceIdentifier extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null deviceIdentifier The identifier that is to be associated with every Device that references this DeviceDefintiion for the issuer and jurisdication porvided in the DeviceDefinition.udiDeviceIdentifier */
        #[NotBlank]
        public StringPrimitive|string|null $deviceIdentifier = null,
        /** @var UriPrimitive|null issuer The organization that assigns the identifier algorithm */
        #[NotBlank]
        public ?UriPrimitive $issuer = null,
        /** @var UriPrimitive|null jurisdiction The jurisdiction to which the deviceIdentifier applies */
        #[NotBlank]
        public ?UriPrimitive $jurisdiction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
