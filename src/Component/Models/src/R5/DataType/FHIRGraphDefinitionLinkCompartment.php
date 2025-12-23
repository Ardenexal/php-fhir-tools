<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element GraphDefinition.link.compartment
 * @description Compartment Consistency Rules.
 */
class FHIRGraphDefinitionLinkCompartment extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGraphCompartmentUseType use where | requires */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGraphCompartmentUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGraphCompartmentRuleType rule identical | matching | different | custom */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRGraphCompartmentRuleType $rule = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCompartmentTypeType code Patient | Encounter | RelatedPerson | Practitioner | Device | EpisodeOfCare */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCompartmentTypeType $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string expression Custom rule, as a FHIRPath expression */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $expression = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string description Documentation for FHIRPath expression */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $description = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
