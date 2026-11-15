<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R4;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\AbstractResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureReportResource;

/**
 * The IN side of R4 `Measure/$submit-data`.
 *
 * Both parameters are resource-typed, and `resource` is `0..*` — a **repeated** resource-slot
 * parameter, which nothing else in the M01 fixture set covers. Its declared type is the abstract
 * `Resource`, the one case `isResourceType()` answers without consulting the registry (no concrete
 * class is registered under that name).
 */
final class MeasureSubmitDataInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'measureReport',
            phpName: 'measureReport',
            use: 'in',
            min: 1,
            max: '1',
            type: 'MeasureReport',
        )]
        public readonly ?MeasureReportResource $measureReport = null,
        /**
         * @var list<AbstractResource>
         */
        #[FhirOperationParameter(
            name: 'resource',
            phpName: 'resource',
            use: 'in',
            min: 0,
            max: '*',
            type: 'Resource',
        )]
        public readonly array $resource = [],
    ) {
    }
}
