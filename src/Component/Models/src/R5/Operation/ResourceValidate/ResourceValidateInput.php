<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Operation\ResourceValidate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\UsageContext;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\AbstractResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Resource-validate',
    use: 'in',
    version: 'R5',
    operation: 'ResourceValidate',
    path: '',
)]
final class ResourceValidateInput
{
    /**
     * @param list<UsageContext> $usageContext
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'resource',
            phpName: 'resource',
            use: 'in',
            min: 0,
            max: '1',
            type: 'Resource',
            documentation: 'Must be present unless the mode is "delete"',
        )]
        public readonly ?AbstractResource $resource = null,
        #[FhirOperationParameter(
            name: 'mode',
            phpName: 'mode',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
            documentation: 'Default is \'no action\'; (e.g. general validation)',
        )]
        public readonly ?string $mode = null,
        #[FhirOperationParameter(
            name: 'profile',
            phpName: 'profile',
            use: 'in',
            min: 0,
            max: '1',
            type: 'canonical',
            documentation: 'If this is nominated, then the resource is validated against this specific profile. If a profile is nominated, and the server cannot validate against the nominated profile, it SHALL return an error',
        )]
        public readonly ?string $profile = null,
        #[FhirOperationParameter(
            name: 'usageContext',
            phpName: 'usageContext',
            use: 'in',
            min: 0,
            max: '*',
            type: 'UsageContext',
            documentation: 'Indicates an implementation context that applies to this validation.  Influences which [additionalBindings](terminologies.html#binding) are relevant.  NOTE: Expectations around subsumption testing, etc. are not yet defined and may be server-specific.',
        )]
        public readonly array $usageContext = [],
    ) {
    }
}
