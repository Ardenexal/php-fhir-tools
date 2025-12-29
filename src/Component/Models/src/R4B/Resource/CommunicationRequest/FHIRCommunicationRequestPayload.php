<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description Text, attachment(s), or resource(s) to be communicated to the recipient.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CommunicationRequest', elementPath: 'CommunicationRequest.payload', fhirVersion: 'R4B')]
class FHIRCommunicationRequestPayload extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference contentX Message part content */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference|null $contentX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
