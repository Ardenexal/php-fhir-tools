<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/BL',
    name: 'BL',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: ['nullFlavor', 'value'],
)]
#[FHIRPathInvariant(
    key: 'value-null',
    severity: 'error',
    expression: '(value | nullFlavor).count() = 1',
    human: 'value and nullFlavor are mutually exclusive (one must be present)',
)]
class BL extends ANY
{
    public function __construct(
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@value')]
        public ?bool $value = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            nullFlavor: $nullFlavor,
        );
    }
}
