<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Financial Management)
 * @see http://hl7.org/fhir/StructureDefinition/EnrollmentResponse
 * @description This resource provides enrollment and plan details from the processing of an EnrollmentRequest resource.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'EnrollmentResponse',
	version: '5.0.0',
	url: 'http://hl7.org/fhir/StructureDefinition/EnrollmentResponse',
	fhirVersion: 'R5',
)]
class FHIREnrollmentResponse extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType language Language of the resource content */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Business Identifier */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFinancialResourceStatusCodesType status active | cancelled | draft | entered-in-error */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFinancialResourceStatusCodesType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference request Claim reference */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIREnrollmentOutcomeType outcome queued | complete | error | partial */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIREnrollmentOutcomeType $outcome = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string disposition Disposition Message */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $disposition = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime created Creation date */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime $created = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference organization Insurer */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $organization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference requestProvider Responsible practitioner */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $requestProvider = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
