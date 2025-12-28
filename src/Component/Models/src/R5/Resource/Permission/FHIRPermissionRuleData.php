<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description A description or definition of which activities are allowed to be done on the data.
 */
#[FHIRBackboneElement(parentResource: 'Permission', elementPath: 'Permission.rule.data', fhirVersion: 'R5')]
class FHIRPermissionRuleData extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRPermissionRuleDataResource> resource Explicit FHIR Resource references */
        public array $resource = [],
        /** @var array<FHIRCoding> security Security tag code on .meta.security */
        public array $security = [],
        /** @var array<FHIRPeriod> period Timeframe encompasing data create/update */
        public array $period = [],
        /** @var FHIRExpression|null expression Expression identifying the data */
        public ?\FHIRExpression $expression = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
