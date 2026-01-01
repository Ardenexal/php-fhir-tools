<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies a concept from an external specification that roughly corresponds to this element.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.mapping', fhirVersion: 'R5')]
class FHIRElementDefinitionMapping extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRId|null identity Reference to mapping declaration */
        #[NotBlank]
        public ?FHIRId $identity = null,
        /** @var FHIRMimeTypesType|null language Computable language of mapping */
        public ?FHIRMimeTypesType $language = null,
        /** @var FHIRString|string|null map Details of the mapping */
        #[NotBlank]
        public FHIRString|string|null $map = null,
        /** @var FHIRMarkdown|null comment Comments about the mapping or its use */
        public ?FHIRMarkdown $comment = null,
    ) {
        parent::__construct($id, $extension);
    }
}
