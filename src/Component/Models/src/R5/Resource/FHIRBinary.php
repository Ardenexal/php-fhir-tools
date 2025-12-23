<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/Binary
 * @description A resource that represents the data of a single raw artifact as digital content accessible in its native format.  A Binary resource can contain any content, whether text, image, pdf, zip archive, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Binary', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Binary', fhirVersion: 'R5')]
class FHIRBinary extends FHIRResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMimeTypesType contentType MimeType of the binary content */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRMimeTypesType $contentType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference securityContext Identifies another resource to use as proxy when enforcing access control */
		public ?FHIRReference $securityContext = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBase64Binary data The actual content */
		public ?FHIRBase64Binary $data = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language);
	}
}
