<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Consent.policyBasis
 * @description A Reference or URL used to uniquely identify the policy the organization will enforce for this Consent. This Reference or URL should be specific to the version of the policy and should be dereferencable to a computable policy of some form.
 */
class FHIRConsentPolicyBasis extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference reference Reference backing policy resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $reference = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl url URL to a computable backing policy */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl $url = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
