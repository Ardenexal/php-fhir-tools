<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/Parameters
 * @description This resource is used to pass information into and back from an operation (whether invoked directly from REST or within a messaging environment).  It is not persisted or allowed to be referenced by other resources.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Parameters', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Parameters', fhirVersion: 'R5')]
class FHIRParameters extends FHIRResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRParametersParameter> parameter Operation Parameter */
		public array $parameter = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language);
	}
}
