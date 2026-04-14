<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @see http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-isNavigable
 *
 * @description Indicates whether the relationship is intended to be navigated when selecting a code
 */
#[FHIRExtensionDefinition(url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-isNavigable', fhirVersion: 'R4')]
class SupportedConceptRelationshipIsNavigableExtension extends Extension
{
    public function __construct(
        /** @var bool|null valueBoolean Value of extension */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $valueBoolean = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://terminology.hl7.org/StructureDefinition/ext-mif-relationship-isNavigable',
            value: $this->valueBoolean,
        );
    }
}
