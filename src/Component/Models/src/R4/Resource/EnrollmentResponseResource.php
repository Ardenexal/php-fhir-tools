<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/EnrollmentResponse
 * @description This resource provides enrollment and plan details from the processing of an EnrollmentRequest resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'EnrollmentResponse',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/EnrollmentResponse',
	fhirVersion: 'R4',
)]
class EnrollmentResponseResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business Identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference request Claim reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType outcome queued | complete | error | partial */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ClaimProcessingCodesType $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string disposition Disposition Message */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $disposition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive created Creation date */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference organization Insurer */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $organization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference requestProvider Responsible practitioner */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $requestProvider = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
