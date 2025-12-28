<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description An organization that distributes the packaged device.
 */
#[FHIRBackboneElement(parentResource: 'DeviceDefinition', elementPath: 'DeviceDefinition.packaging.distributor', fhirVersion: 'R5')]
class FHIRDeviceDefinitionPackagingDistributor extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Distributor's human-readable name */
        public \FHIRString|string|null $name = null,
        /** @var array<FHIRReference> organizationReference Distributor as an Organization resource */
        public array $organizationReference = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
