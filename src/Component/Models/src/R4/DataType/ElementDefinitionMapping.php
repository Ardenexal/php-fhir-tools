<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies a concept from an external specification that roughly corresponds to this element.
 */
#[FHIRComplexType(typeName: 'ElementDefinition.mapping', fhirVersion: 'R4')]
class ElementDefinitionMapping extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var IdPrimitive|null identity Reference to mapping declaration */
        #[NotBlank]
        public ?IdPrimitive $identity = null,
        /** @var MimeTypesType|null language Computable language of mapping */
        public ?MimeTypesType $language = null,
        /** @var StringPrimitive|string|null map Details of the mapping */
        #[NotBlank]
        public StringPrimitive|string|null $map = null,
        /** @var StringPrimitive|string|null comment Comments about the mapping or its use */
        public StringPrimitive|string|null $comment = null,
    ) {
        parent::__construct($id, $extension);
    }
}
