<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ContractResourcePublicationStatusCodesType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Precusory content developed with a focus and intent of supporting the formation a Contract instance, which may be associated with and transformable into a Contract.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.contentDefinition', fhirVersion: 'R4')]
class ContractContentDefinition extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null type Content structure and use */
        #[NotBlank]
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null subType Detailed Content Type Definition */
        public ?CodeableConcept $subType = null,
        /** @var Reference|null publisher Publisher Entity */
        public ?Reference $publisher = null,
        /** @var DateTimePrimitive|null publicationDate When published */
        public ?DateTimePrimitive $publicationDate = null,
        /** @var ContractResourcePublicationStatusCodesType|null publicationStatus amended | appended | cancelled | disputed | entered-in-error | executable | executed | negotiable | offered | policy | rejected | renewed | revoked | resolved | terminated */
        #[NotBlank]
        public ?ContractResourcePublicationStatusCodesType $publicationStatus = null,
        /** @var MarkdownPrimitive|null copyright Publication Ownership */
        public ?MarkdownPrimitive $copyright = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
