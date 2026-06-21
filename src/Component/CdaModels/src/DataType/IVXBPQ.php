<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/IVXB-PQ',
    name: 'IVXB_PQ',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class IVXBPQ extends PQ
{
    /**
     * @param list<PQR> $translation
     */
    public function __construct(
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@inclusive')]
        public ?bool $inclusive = null,
        ?string $unit = null,
        ?float $value = null,
        array $translation = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            unit: $unit,
            value: $value,
            translation: $translation,
            nullFlavor: $nullFlavor,
        );
    }
}
