<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The artifact related to the cited artifact.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.relatesTo', fhirVersion: 'R5')]
class FHIRCitationCitedArtifactRelatesTo extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRRelatedArtifactTypeExpandedType|null type documentation | justification | citation | predecessor | successor | derived-from | depends-on | composed-of | part-of | amends | amended-with | appends | appended-with | cites | cited-by | comments-on | comment-in | contains | contained-in | corrects | correction-in | replaces | replaced-with | retracts | retracted-by | signs | similar-to | supports | supported-with | transforms | transformed-into | transformed-with | documents | specification-of | created-with | cite-as | reprint | reprint-of */
        #[NotBlank]
        public ?FHIRRelatedArtifactTypeExpandedType $type = null,
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
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
