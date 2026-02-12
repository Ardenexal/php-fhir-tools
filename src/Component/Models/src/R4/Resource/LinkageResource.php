<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Care)
 * @see http://hl7.org/fhir/StructureDefinition/Linkage
 * @description Identifies two or more records (resource instances) that refer to the same real-world "occurrence".
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Linkage', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Linkage', fhirVersion: 'R4')]
class LinkageResource extends DomainResourceResource
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
		/** @var null|bool active Whether this linkage assertion is active or not */
		public ?bool $active = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference author Who is responsible for linkages */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $author = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Linkage\LinkageItem> item Item to be linked */
		public array $item = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
