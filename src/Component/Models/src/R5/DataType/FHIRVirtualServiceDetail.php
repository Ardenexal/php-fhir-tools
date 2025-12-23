<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/VirtualServiceDetail
 * @description Virtual Service Contact Details.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'VirtualServiceDetail', fhirVersion: 'R5')]
class FHIRVirtualServiceDetail extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding channelType Channel Type */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding $channelType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContactPoint|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtendedContactDetail addressX Contact address/number */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContactPoint|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtendedContactDetail|null $addressX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl> additionalInfo Address to see alternative connection details */
		public array $additionalInfo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt maxParticipants Maximum number of participants supported by the virtual service */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPositiveInt $maxParticipants = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string sessionKey Session Key required by the virtual service */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $sessionKey = null,
	) {
		parent::__construct($id, $extension);
	}
}
