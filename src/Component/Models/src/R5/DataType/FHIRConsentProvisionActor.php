<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Consent.provision.actor
 * @description Who or what is controlled by this provision. Use group to identify a set of actors by some property they share (e.g. 'admitting officers').
 */
class FHIRConsentProvisionActor extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept role How the actor is involved */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $role = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference reference Resource for the actor (or group, by role) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $reference = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
