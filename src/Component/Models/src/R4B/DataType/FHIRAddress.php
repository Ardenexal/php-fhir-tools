<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Address
 * @description An address expressed using postal conventions (as opposed to GPS or other location definition formats).  This data type may be used to convey addresses for use in delivering mail as well as for visiting locations which might not be valid for mail delivery.  There are a variety of postal address formats defined around the world.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Address', fhirVersion: 'R4B')]
class FHIRAddress extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAddressUseType use home | work | temp | old | billing - purpose of this address */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAddressUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAddressTypeType type postal | physical | both */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAddressTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string text Text representation of the address */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string> line Street name, number, direction & P.O. Box etc. */
		public array $line = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string city Name of city, town etc. */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $city = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string district District name (aka county) */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $district = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string state Sub-unit of country (abbreviations ok) */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $state = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string postalCode Postal code for area */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $postalCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string country Country (e.g. can be ISO 3166 2 or 3 letter code) */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $country = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod period Time period when address was/is in use */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRPeriod $period = null,
	) {
		parent::__construct($id, $extension);
	}
}
