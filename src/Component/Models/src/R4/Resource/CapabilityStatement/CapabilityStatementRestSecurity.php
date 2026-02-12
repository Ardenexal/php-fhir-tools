<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

/**
 * @description Information about security implementation from an interface perspective - what a client needs to know.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest.security', fhirVersion: 'R4')]
class CapabilityStatementRestSecurity extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|bool cors Adds CORS Headers (http://enable-cors.org/) */
		public ?bool $cors = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> service OAuth | SMART-on-FHIR | NTLM | Basic | Kerberos | Certificates */
		public array $service = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive description General description of how security works */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $description = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
