<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-complex-type ElementDefinition.binding.additional
 * @description Additional bindings that help applications implementing this element. Additional bindings do not replace the main binding but provide more information and/or context.
 */
class FHIRElementDefinitionBindingAdditional extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdditionalBindingPurposeVSType purpose maximum | minimum | required | extensible | candidate | current | preferred | ui | starter | component */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAdditionalBindingPurposeVSType $purpose = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical valueSet The value set for the additional binding */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCanonical $valueSet = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown documentation Documentation of the purpose of use of the binding */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMarkdown $documentation = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string shortDoco Concise documentation - for summary tables */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $shortDoco = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUsageContext> usage Qualifies the usage - jurisdiction, gender, workflow status etc. */
		public array $usage = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean any Whether binding can applies to all repeats, or just one */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBoolean $any = null,
	) {
		parent::__construct($id, $extension);
	}
}
