<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element CapabilityStatement.rest.security
 * @description Information about security implementation from an interface perspective - what a client needs to know.
 */
class FHIRCapabilityStatementRestSecurity extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean cors Adds CORS Headers (http://enable-cors.org/) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $cors = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> service OAuth | SMART-on-FHIR | NTLM | Basic | Kerberos | Certificates */
		public array $service = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown description General description of how security works */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown $description = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}
