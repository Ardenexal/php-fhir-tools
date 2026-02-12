<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/Parameters
 * @description This resource is a non-persisted resource used to pass information into and back from an [operation](operations.html). It has no other use, and there is no RESTful endpoint associated with it.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Parameters', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Parameters', fhirVersion: 'R4')]
class ParametersResource extends ResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Parameters\ParametersParameter> parameter Operation Parameter */
		public array $parameter = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language);
	}
}
