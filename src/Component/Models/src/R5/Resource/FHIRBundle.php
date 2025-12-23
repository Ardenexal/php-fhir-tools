<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/Bundle
 * @description A container for a collection of resources.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Bundle', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Bundle', fhirVersion: 'R5')]
class FHIRBundle extends FHIRResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier identifier Persistent identifier for the bundle */
		public ?FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBundleTypeType type document | message | transaction | transaction-response | batch | batch-response | history | searchset | collection | subscription-notification */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRBundleTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRInstant timestamp When the bundle was assembled */
		public ?FHIRInstant $timestamp = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUnsignedInt total If search, the total number of matches */
		public ?FHIRUnsignedInt $total = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBundleLink> link Links related to this Bundle */
		public array $link = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBundleEntry> entry Entry in the bundle - will have a resource or information */
		public array $entry = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRSignature signature Digital Signature */
		public ?FHIRSignature $signature = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource issues Issues with the Bundle */
		public ?FHIRResource $issues = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language);
	}
}
