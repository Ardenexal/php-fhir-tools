<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Communication.payload
 * @description Text, attachment(s), or resource(s) that was communicated to the recipient.
 */
class FHIRCommunicationPayload extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference contentX Message part content */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRAttachment|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference|null $contentX = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
