<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUrl;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRRelatedArtifactTypeType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/RelatedArtifact
 *
 * @description Related artifacts such as additional documentation, justification, or bibliographic references.
 */
#[FHIRComplexType(typeName: 'RelatedArtifact', fhirVersion: 'R4B')]
class FHIRRelatedArtifact extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRRelatedArtifactTypeType|null type documentation | justification | citation | predecessor | successor | derived-from | depends-on | composed-of */
        #[NotBlank]
        public ?FHIRRelatedArtifactTypeType $type = null,
        /** @var FHIRString|string|null label Short label */
        public FHIRString|string|null $label = null,
        /** @var FHIRString|string|null display Brief description of the related artifact */
        public FHIRString|string|null $display = null,
        /** @var FHIRMarkdown|null citation Bibliographic citation for the artifact */
        public ?FHIRMarkdown $citation = null,
        /** @var FHIRUrl|null url Where the artifact can be accessed */
        public ?FHIRUrl $url = null,
        /** @var FHIRAttachment|null document What document is being referenced */
        public ?FHIRAttachment $document = null,
        /** @var FHIRCanonical|null resource What resource is being referenced */
        public ?FHIRCanonical $resource = null,
    ) {
        parent::__construct($id, $extension);
    }
}
