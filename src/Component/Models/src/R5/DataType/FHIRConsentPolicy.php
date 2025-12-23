<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Consent.policy
 * @description The references to the policies that are included in this consent scope. Policies may be organizational, but are often defined jurisdictionally, or in law.
 */
class FHIRConsentPolicy extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri authority Enforcement source for policy */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri $authority = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri uri Specific policy covered by this consent */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri $uri = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
