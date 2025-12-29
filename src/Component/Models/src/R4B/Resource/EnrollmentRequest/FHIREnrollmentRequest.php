<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/EnrollmentRequest
 * @description This resource provides the insurance enrollment details to the insurer regarding a specified coverage.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'EnrollmentRequest',
	version: '4.3.0',
	url: 'http://hl7.org/fhir/StructureDefinition/EnrollmentRequest',
	fhirVersion: 'R4B',
)]
class FHIREnrollmentRequest extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier> identifier Business Identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRFinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRFinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime created Creation date */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference insurer Target */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $insurer = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference provider Responsible practitioner */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $provider = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference candidate The subject to be enrolled */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $candidate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference coverage Insurance information */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $coverage = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
