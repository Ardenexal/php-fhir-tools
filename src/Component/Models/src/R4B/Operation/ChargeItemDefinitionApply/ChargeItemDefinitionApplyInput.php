<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Operation\ChargeItemDefinitionApply;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Reference;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/ChargeItemDefinition-apply',
    use: 'in',
    version: 'R4B',
    operation: 'ChargeItemDefinitionApply',
    path: '',
)]
final class ChargeItemDefinitionApplyInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'chargeItem',
            phpName: 'chargeItem',
            use: 'in',
            min: 1,
            max: '1',
            type: 'Reference',
            documentation: 'The ChargeItem on which the definition is to ba applies',
        )]
        public readonly ?Reference $chargeItem = null,
        #[FhirOperationParameter(
            name: 'account',
            phpName: 'account',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Reference',
            documentation: 'The account in context, if any',
        )]
        public readonly ?Reference $account = null,
    ) {
    }
}
