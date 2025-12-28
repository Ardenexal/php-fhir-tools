<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Additional bindings that help applications implementing this element. Additional bindings do not replace the main binding but provide more information and/or context.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.binding.additional', fhirVersion: 'R5')]
class FHIRElementDefinitionBindingAdditional extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRAdditionalBindingPurposeVSType|null purpose maximum | minimum | required | extensible | candidate | current | preferred | ui | starter | component */
        #[NotBlank]
        public ?\FHIRAdditionalBindingPurposeVSType $purpose = null,
        /** @var FHIRCanonical|null valueSet The value set for the additional binding */
        #[NotBlank]
        public ?\FHIRCanonical $valueSet = null,
        /** @var FHIRMarkdown|null documentation Documentation of the purpose of use of the binding */
        public ?\FHIRMarkdown $documentation = null,
        /** @var FHIRString|string|null shortDoco Concise documentation - for summary tables */
        public \FHIRString|string|null $shortDoco = null,
        /** @var array<FHIRUsageContext> usage Qualifies the usage - jurisdiction, gender, workflow status etc. */
        public array $usage = [],
        /** @var FHIRBoolean|null any Whether binding can applies to all repeats, or just one */
        public ?\FHIRBoolean $any = null,
    ) {
        parent::__construct($id, $extension);
    }
}
