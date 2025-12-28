<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Precusory content developed with a focus and intent of supporting the formation a Contract instance, which may be associated with and transformable into a Contract.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.contentDefinition', fhirVersion: 'R5')]
class FHIRContractContentDefinition extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type Content structure and use */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null subType Detailed Content Type Definition */
        public ?FHIRCodeableConcept $subType = null,
        /** @var FHIRReference|null publisher Publisher Entity */
        public ?FHIRReference $publisher = null,
        /** @var FHIRDateTime|null publicationDate When published */
        public ?FHIRDateTime $publicationDate = null,
        /** @var FHIRContractResourcePublicationStatusCodesType|null publicationStatus amended | appended | cancelled | disputed | entered-in-error | executable + */
        #[NotBlank]
        public ?FHIRContractResourcePublicationStatusCodesType $publicationStatus = null,
        /** @var FHIRMarkdown|null copyright Publication Ownership */
        public ?FHIRMarkdown $copyright = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
