<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @author HL7 FHIR Standard
 * @see http://hl7.org/fhir/StructureDefinition/Signature
 * @description A signature along with supporting context. The signature may be a digital signature that is cryptographic in nature, or some other signature acceptable to the domain. This other signature may be as simple as a graphical image representing a hand-written signature, or a signature ceremony Different signature approaches have different utilities.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType(typeName: 'Signature', fhirVersion: 'R4')]
class FHIRSignature extends FHIRElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding> type Indication of the reason the entity signed the object(s) */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant when When the signature was created */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant $when = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference who Who signed */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $who = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference onBehalfOf The party represented */
		public ?FHIRReference $onBehalfOf = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMimeTypesType targetFormat The technical format of the signed resources */
		public ?FHIRMimeTypesType $targetFormat = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMimeTypesType sigFormat The technical format of the signature */
		public ?FHIRMimeTypesType $sigFormat = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBase64Binary data The actual signature content (XML DigSig. JWS, picture, etc.) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBase64Binary $data = null,
	) {
		parent::__construct($id, $extension);
	}
}
