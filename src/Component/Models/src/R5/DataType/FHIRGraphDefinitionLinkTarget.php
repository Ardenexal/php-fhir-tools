<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element GraphDefinition.link.target
 * @description Potential target for the link.
 */
class FHIRGraphDefinitionLinkTarget extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResourceTypeType type Type of resource this link refers to */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResourceTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string params Criteria for reverse lookup */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $params = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical profile Profile for the target resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCanonical $profile = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRGraphDefinitionLinkTargetCompartment> compartment Compartment Consistency Rules */
		public array $compartment = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRGraphDefinitionLink> link Additional links from target resource */
		public array $link = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
