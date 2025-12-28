<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/VirtualServiceDetail
 * @description Virtual Service Contact Details.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'VirtualServiceDetail', fhirVersion: 'R5')]
class FHIRVirtualServiceDetail extends FHIRDataType
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding channelType Channel Type */
		public ?FHIRCoding $channelType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactPoint|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtendedContactDetail addressX Contact address/number */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|FHIRContactPoint|FHIRExtendedContactDetail|null $addressX = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl> additionalInfo Address to see alternative connection details */
		public array $additionalInfo = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt maxParticipants Maximum number of participants supported by the virtual service */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRPositiveInt $maxParticipants = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string sessionKey Session Key required by the virtual service */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $sessionKey = null,
	) {
		parent::__construct($id, $extension);
	}
}
