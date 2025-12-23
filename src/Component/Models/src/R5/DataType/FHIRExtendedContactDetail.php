<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/ExtendedContactDetail
 * @description Specifies contact information for a specific purpose over a period of time, might be handled/monitored by a specific named person or organization.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'ExtendedContactDetail', fhirVersion: 'R5')]
class FHIRExtendedContactDetail extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept purpose The type of contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $purpose = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRHumanName> name Name of an individual to contact */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContactPoint> telecom Contact details (e.g.phone/fax/url) */
		public array $telecom = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddress address Address for the contact */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddress $address = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference organization This contact detail is handled/monitored by a specific organization */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference $organization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod period Period that this contact was valid for usage */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod $period = null,
	) {
		parent::__construct($id, $extension);
	}
}
