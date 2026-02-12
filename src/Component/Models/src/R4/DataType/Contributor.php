<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Contributor
 *
 * @description A contributor to the content of a knowledge asset, including authors, editors, reviewers, and endorsers.
 */
#[FHIRComplexType(typeName: 'Contributor', fhirVersion: 'R4')]
class Contributor extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var ContributorTypeType|null type author | editor | reviewer | endorser */
        #[NotBlank]
        public ?ContributorTypeType $type = null,
        /** @var StringPrimitive|string|null name Who contributed the content */
        #[NotBlank]
        public StringPrimitive|string|null $name = null,
        /** @var array<ContactDetail> contact Contact details of the contributor */
        public array $contact = [],
    ) {
        parent::__construct($id, $extension);
    }
}
