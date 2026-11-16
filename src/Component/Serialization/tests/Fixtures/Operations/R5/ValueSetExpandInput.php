<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\R5;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ValueSetResource;

/**
 * Hand-written stand-in for the generated R5 `ValueSet/$expand` IN class.
 *
 * `$expand` is the class-B counterpart to `$lookup`: its sole OUT parameter is named `return` and is
 * resource-typed, so the response is a bare `ValueSet` and there is no Output class at all. The IN
 * direction is still a `Parameters` body, which is what this class maps.
 *
 * Three things `$lookup` could not exercise live here:
 *
 * 1. `valueSet` is typed `ValueSet` — a **resource-typed** parameter, so it takes
 *    `ParametersParameter.resource` rather than `value[x]`. That branch shipped untested.
 * 2. Four parameters carry hyphens on the wire (`exclude-system`, `system-version`,
 *    `check-system-version`, `force-system-version`). None is a legal PHP identifier, so the
 *    wire name and the PHP name genuinely diverge and both have to be stored.
 * 3. `offset` and `count` are `integer`, and `count` collides with nothing but reads as a
 *    reserved-ish name — the bare-int path through the value slot.
 *
 * Parameter documentation is deliberately omitted: it is long markdown that only feeds generated
 * docblocks, and this fixture exists to prove mapping. Every other field is diffed against
 * http://hl7.org/fhir/OperationDefinition/ValueSet-expand by OperationFixtureFidelityTest.
 */
final class ValueSetExpandInput
{
    public function __construct(
        #[FhirOperationParameter(
            name: 'url',
            phpName: 'url',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
        )]
        public readonly ?string $url = null,
        #[FhirOperationParameter(
            name: 'valueSet',
            phpName: 'valueSet',
            use: 'in',
            min: 0,
            max: '1',
            type: 'ValueSet',
        )]
        public readonly ?ValueSetResource $valueSet = null,
        #[FhirOperationParameter(
            name: 'valueSetVersion',
            phpName: 'valueSetVersion',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
        )]
        public readonly ?string $valueSetVersion = null,
        #[FhirOperationParameter(
            name: 'context',
            phpName: 'context',
            use: 'in',
            min: 0,
            max: '1',
            type: 'uri',
        )]
        public readonly ?string $context = null,
        #[FhirOperationParameter(
            name: 'contextDirection',
            phpName: 'contextDirection',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
        )]
        public readonly ?string $contextDirection = null,
        #[FhirOperationParameter(
            name: 'filter',
            phpName: 'filter',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
        )]
        public readonly ?string $filter = null,
        #[FhirOperationParameter(
            name: 'date',
            phpName: 'date',
            use: 'in',
            min: 0,
            max: '1',
            type: 'dateTime',
        )]
        public readonly ?string $date = null,
        #[FhirOperationParameter(
            name: 'offset',
            phpName: 'offset',
            use: 'in',
            min: 0,
            max: '1',
            type: 'integer',
        )]
        public readonly ?int $offset = null,
        #[FhirOperationParameter(
            name: 'count',
            phpName: 'count',
            use: 'in',
            min: 0,
            max: '1',
            type: 'integer',
        )]
        public readonly ?int $count = null,
        #[FhirOperationParameter(
            name: 'includeDesignations',
            phpName: 'includeDesignations',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
        )]
        public readonly ?bool $includeDesignations = null,
        /** @var list<string> */
        #[FhirOperationParameter(
            name: 'designation',
            phpName: 'designation',
            use: 'in',
            min: 0,
            max: '*',
            type: 'string',
        )]
        public readonly array $designation = [],
        #[FhirOperationParameter(
            name: 'includeDefinition',
            phpName: 'includeDefinition',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
        )]
        public readonly ?bool $includeDefinition = null,
        #[FhirOperationParameter(
            name: 'activeOnly',
            phpName: 'activeOnly',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
        )]
        public readonly ?bool $activeOnly = null,
        /** @var list<string> */
        #[FhirOperationParameter(
            name: 'useSupplement',
            phpName: 'useSupplement',
            use: 'in',
            min: 0,
            max: '*',
            type: 'canonical',
        )]
        public readonly array $useSupplement = [],
        #[FhirOperationParameter(
            name: 'excludeNested',
            phpName: 'excludeNested',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
        )]
        public readonly ?bool $excludeNested = null,
        #[FhirOperationParameter(
            name: 'excludeNotForUI',
            phpName: 'excludeNotForUI',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
        )]
        public readonly ?bool $excludeNotForUI = null,
        #[FhirOperationParameter(
            name: 'excludePostCoordinated',
            phpName: 'excludePostCoordinated',
            use: 'in',
            min: 0,
            max: '1',
            type: 'boolean',
        )]
        public readonly ?bool $excludePostCoordinated = null,
        #[FhirOperationParameter(
            name: 'displayLanguage',
            phpName: 'displayLanguage',
            use: 'in',
            min: 0,
            max: '1',
            type: 'code',
        )]
        public readonly ?string $displayLanguage = null,
        /** @var list<string> */
        #[FhirOperationParameter(
            name: 'property',
            phpName: 'property',
            use: 'in',
            min: 0,
            max: '*',
            type: 'string',
        )]
        public readonly array $property = [],
        /** @var list<string> */
        #[FhirOperationParameter(
            name: 'exclude-system',
            phpName: 'excludeSystem',
            use: 'in',
            min: 0,
            max: '*',
            type: 'canonical',
        )]
        public readonly array $excludeSystem = [],
        /** @var list<string> */
        #[FhirOperationParameter(
            name: 'system-version',
            phpName: 'systemVersion',
            use: 'in',
            min: 0,
            max: '*',
            type: 'canonical',
        )]
        public readonly array $systemVersion = [],
        /** @var list<string> */
        #[FhirOperationParameter(
            name: 'check-system-version',
            phpName: 'checkSystemVersion',
            use: 'in',
            min: 0,
            max: '*',
            type: 'canonical',
        )]
        public readonly array $checkSystemVersion = [],
        /** @var list<string> */
        #[FhirOperationParameter(
            name: 'force-system-version',
            phpName: 'forceSystemVersion',
            use: 'in',
            min: 0,
            max: '*',
            type: 'canonical',
        )]
        public readonly array $forceSystemVersion = [],
    ) {
    }
}
