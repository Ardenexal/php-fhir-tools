<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 * @see http://hl7.org/fhir/StructureDefinition/Binary
 * @description A resource that represents the data of a single raw artifact as digital content accessible in its native format.  A Binary resource can contain any content, whether text, image, pdf, zip archive, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Binary', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Binary', fhirVersion: 'R4')]
class BinaryResource extends ResourceResource
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
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType contentType MimeType of the binary content */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType $contentType = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference securityContext Identifies another resource to use as proxy when enforcing access control */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $securityContext = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive data The actual content */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive $data = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language);
	}
}
