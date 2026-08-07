<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\ResourceValidate;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AbstractResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/Resource-validate',
    use: 'in',
    version: 'R4',
    operation: 'ResourceValidate',
    path: '',
)]
final class ResourceValidateInput
{
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
            type: 'uri',
            documentation: 'If this is nominated, then the resource is validated against this specific profile. If a profile is nominated, and the server cannot validate against the nominated profile, it SHALL return an error',
        )]
        public readonly ?string $profile = null,
    ) {
    }
}
