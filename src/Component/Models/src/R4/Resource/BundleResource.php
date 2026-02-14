<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/Bundle
 * @description A container for a collection of resources.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Bundle', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Bundle', fhirVersion: 'R4')]
class BundleResource extends ResourceResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier identifier Persistent identifier for the bundle */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\BundleTypeType type document | message | transaction | transaction-response | batch | batch-response | history | searchset | collection */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\BundleTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive timestamp When the bundle was assembled */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\InstantPrimitive $timestamp = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive total If search, the total number of matches */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive $total = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleLink> link Links related to this Bundle */
		public array $link = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntry> entry Entry in the bundle - will have a resource or information */
		public array $entry = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature signature Digital Signature */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Signature $signature = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language);
	}
}
