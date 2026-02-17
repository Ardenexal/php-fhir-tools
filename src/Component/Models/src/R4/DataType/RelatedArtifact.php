<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/RelatedArtifact
 *
 * @description Related artifacts such as additional documentation, justification, or bibliographic references.
 */
#[FHIRComplexType(typeName: 'RelatedArtifact', fhirVersion: 'R4')]
class RelatedArtifact extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var RelatedArtifactTypeType|null type documentation | justification | citation | predecessor | successor | derived-from | depends-on | composed-of */
        #[NotBlank]
        public ?RelatedArtifactTypeType $type = null,
        /** @var StringPrimitive|string|null label Short label */
        public StringPrimitive|string|null $label = null,
        /** @var StringPrimitive|string|null display Brief description of the related artifact */
        public StringPrimitive|string|null $display = null,
        /** @var MarkdownPrimitive|null citation Bibliographic citation for the artifact */
        public ?MarkdownPrimitive $citation = null,
        /** @var UrlPrimitive|null url Where the artifact can be accessed */
        public ?UrlPrimitive $url = null,
        /** @var Attachment|null document What document is being referenced */
        public ?Attachment $document = null,
        /** @var CanonicalPrimitive|null resource What resource is being referenced */
        public ?CanonicalPrimitive $resource = null,
    ) {
        parent::__construct($id, $extension);
    }
}
