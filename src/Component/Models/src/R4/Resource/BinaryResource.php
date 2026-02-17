<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\MimeTypesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\Base64BinaryPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Binary
 *
 * @description A resource that represents the data of a single raw artifact as digital content accessible in its native format.  A Binary resource can contain any content, whether text, image, pdf, zip archive, etc.
 */
#[FhirResource(type: 'Binary', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Binary', fhirVersion: 'R4')]
class BinaryResource extends ResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var MimeTypesType|null contentType MimeType of the binary content */
        #[NotBlank]
        public ?MimeTypesType $contentType = null,
        /** @var Reference|null securityContext Identifies another resource to use as proxy when enforcing access control */
        public ?Reference $securityContext = null,
        /** @var Base64BinaryPrimitive|null data The actual content */
        public ?Base64BinaryPrimitive $data = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language);
    }
}
