<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/INT',
    name: 'INT',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
#[FHIRPathInvariant(
    key: 'value-null',
    severity: 'error',
    expression: '(value | nullFlavor).count() = 1',
    human: 'value and nullFlavor are mutually exclusive (one must be present)',
)]
class INTType extends QTY
{
    public function __construct(
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@value')]
        public ?int $value = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            nullFlavor: $nullFlavor,
        );
    }
}
