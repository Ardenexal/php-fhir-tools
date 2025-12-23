<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Signature
 * @description A signature along with supporting context. The signature may be a digital signature that is cryptographic in nature, or some other signature acceptable to the domain. This other signature may be as simple as a graphical image representing a hand-written signature, or a signature ceremony Different signature approaches have different utilities.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Signature', fhirVersion: 'R4B')]
class FHIRSignature extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding> type Indication of the reason the entity signed the object(s) */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInstant when When the signature was created */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRInstant $when = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference who Who signed */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $who = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference onBehalfOf The party represented */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $onBehalfOf = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMimeTypesType targetFormat The technical format of the signed resources */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMimeTypesType $targetFormat = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMimeTypesType sigFormat The technical format of the signature */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMimeTypesType $sigFormat = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBase64Binary data The actual signature content (XML DigSig. JWS, picture, etc.) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBase64Binary $data = null,
	) {
		parent::__construct($id, $extension);
	}
}
