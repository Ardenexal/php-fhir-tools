<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element HealthcareService.eligibility
 * @description Does this service have specific eligibility requirements that need to be met in order to use the service?
 */
class FHIRHealthcareServiceEligibility extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept code Coded value for the eligibility */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown comment Describes the eligibility conditions for the service */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMarkdown $comment = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
