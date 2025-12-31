<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @description Additional bindings that help applications implementing this element. Additional bindings do not replace the main binding but provide more information and/or context.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'ElementDefinition.binding.additional', fhirVersion: 'R5')]
class FHIRElementDefinitionBindingAdditional extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAdditionalBindingPurposeVSType purpose maximum | minimum | required | extensible | candidate | current | preferred | ui | starter | component */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRAdditionalBindingPurposeVSType $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical valueSet The value set for the additional binding */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical $valueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown documentation Documentation of the purpose of use of the binding */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown $documentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string shortDoco Concise documentation - for summary tables */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $shortDoco = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRUsageContext> usage Qualifies the usage - jurisdiction, gender, workflow status etc. */
		public array $usage = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean any Whether binding can applies to all repeats, or just one */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean $any = null,
	) {
		parent::__construct($id, $extension);
	}
}
