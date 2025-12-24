<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates where and when the device is available on the market.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'DeviceDefinition',
    elementPath: 'DeviceDefinition.udiDeviceIdentifier.marketDistribution',
    fhirVersion: 'R5',
)]
class FHIRDeviceDefinitionUdiDeviceIdentifierMarketDistribution extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRPeriod|null marketPeriod Begin and end dates for the commercial distribution of the device */
        #[NotBlank]
        public ?FHIRPeriod $marketPeriod = null,
        /** @var FHIRUri|null subJurisdiction National state or territory where the device is commercialized */
        #[NotBlank]
        public ?FHIRUri $subJurisdiction = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
