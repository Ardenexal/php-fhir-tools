<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPublicationStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRRelatedArtifactTypeType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/RelatedArtifact
 *
 * @description Related artifacts such as additional documentation, justification, or bibliographic references.
 */
#[FHIRComplexType(typeName: 'RelatedArtifact', fhirVersion: 'R5')]
class FHIRRelatedArtifact extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRRelatedArtifactTypeType|null type documentation | justification | citation | predecessor | successor | derived-from | depends-on | composed-of | part-of | amends | amended-with | appends | appended-with | cites | cited-by | comments-on | comment-in | contains | contained-in | corrects | correction-in | replaces | replaced-with | retracts | retracted-by | signs | similar-to | supports | supported-with | transforms | transformed-into | transformed-with | documents | specification-of | created-with | cite-as */
        #[NotBlank]
        public ?FHIRRelatedArtifactTypeType $type = null,
        /** @var array<FHIRCodeableConcept> classifier Additional classifiers */
        public array $classifier = [],
        /** @var FHIRString|string|null label Short label */
        public FHIRString|string|null $label = null,
        /** @var FHIRString|string|null display Brief description of the related artifact */
        public FHIRString|string|null $display = null,
        /** @var FHIRMarkdown|null citation Bibliographic citation for the artifact */
        public ?FHIRMarkdown $citation = null,
        /** @var FHIRAttachment|null document What document is being referenced */
        public ?FHIRAttachment $document = null,
        /** @var FHIRCanonical|null resource What artifact is being referenced */
        public ?FHIRCanonical $resource = null,
        /** @var FHIRReference|null resourceReference What artifact, if not a conformance resource */
        public ?FHIRReference $resourceReference = null,
        /** @var FHIRPublicationStatusType|null publicationStatus draft | active | retired | unknown */
        public ?FHIRPublicationStatusType $publicationStatus = null,
        /** @var FHIRDate|null publicationDate Date of publication of the artifact being referred to */
        public ?FHIRDate $publicationDate = null,
    ) {
        parent::__construct($id, $extension);
    }
}
