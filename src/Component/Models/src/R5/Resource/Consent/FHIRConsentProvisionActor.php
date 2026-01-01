<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Who or what is controlled by this provision. Use group to identify a set of actors by some property they share (e.g. 'admitting officers').
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.provision.actor', fhirVersion: 'R5')]
class FHIRConsentProvisionActor extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept role How the actor is involved */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $role = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference reference Resource for the actor (or group, by role) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $reference = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
