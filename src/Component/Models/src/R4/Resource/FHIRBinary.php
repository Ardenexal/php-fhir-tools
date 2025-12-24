<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBase64Binary;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Binary
 *
 * @description A resource that represents the data of a single raw artifact as digital content accessible in its native format.  A Binary resource can contain any content, whether text, image, pdf, zip archive, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Binary', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Binary', fhirVersion: 'R4')]
class FHIRBinary extends FHIRResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRMimeTypesType|null contentType MimeType of the binary content */
        #[NotBlank]
        public ?FHIRMimeTypesType $contentType = null,
        /** @var FHIRReference|null securityContext Identifies another resource to use as proxy when enforcing access control */
        public ?FHIRReference $securityContext = null,
        /** @var FHIRBase64Binary|null data The actual content */
        public ?FHIRBase64Binary $data = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language);
    }
}
