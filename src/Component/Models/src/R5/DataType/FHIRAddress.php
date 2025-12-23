<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Address
 * @description An address expressed using postal conventions (as opposed to GPS or other location definition formats).  This data type may be used to convey addresses for use in delivering mail as well as for visiting locations which might not be valid for mail delivery.  There are a variety of postal address formats defined around the world.
 * The ISO21090-codedString may be used to provide a coded representation of the contents of strings in an Address.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Address', fhirVersion: 'R5')]
class FHIRAddress extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddressUseType use home | work | temp | old | billing - purpose of this address */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddressUseType $use = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddressTypeType type postal | physical | both */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAddressTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string text Text representation of the address */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string> line Street name, number, direction & P.O. Box etc. */
		public array $line = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string city Name of city, town etc. */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $city = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string district District name (aka county) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $district = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string state Sub-unit of country (abbreviations ok) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $state = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string postalCode Postal code for area */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $postalCode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string country Country (e.g. may be ISO 3166 2 or 3 letter code) */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $country = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod period Time period when address was/is in use */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod $period = null,
	) {
		parent::__construct($id, $extension);
	}
}
