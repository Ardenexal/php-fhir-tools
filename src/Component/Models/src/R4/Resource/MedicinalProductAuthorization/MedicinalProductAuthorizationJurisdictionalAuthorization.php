<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductAuthorization;

/**
 * @description Authorization in areas within a country.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'MedicinalProductAuthorization',
	elementPath: 'MedicinalProductAuthorization.jurisdictionalAuthorization',
	fhirVersion: 'R4',
)]
class MedicinalProductAuthorizationJurisdictionalAuthorization extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier The assigned number for the marketing authorization */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept country Country of authorization */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $country = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> jurisdiction Jurisdiction within a country */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept legalStatusOfSupply The legal status of supply in a jurisdiction or region */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $legalStatusOfSupply = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period validityPeriod The start and expected end date of the authorization */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $validityPeriod = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
