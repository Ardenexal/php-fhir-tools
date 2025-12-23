<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Endpoint.payload
 * @description The set of payloads that are provided/available at this endpoint.
 */
class FHIREndpointPayload extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> type The type of content that may be used at this endpoint (e.g. XDS Discharge summaries) */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMimeTypesType> mimeType Mimetype to send. If not specified, the content could be anything (including no payload, if the connectionType defined this) */
		public array $mimeType = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
