<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Contributor
 *
 * @description A contributor to the content of a knowledge asset, including authors, editors, reviewers, and endorsers.
 */
#[FHIRComplexType(typeName: 'Contributor', fhirVersion: 'R5')]
class FHIRContributor extends FHIRDataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRContributorTypeType|null type author | editor | reviewer | endorser */
        #[NotBlank]
        public ?FHIRContributorTypeType $type = null,
        /** @var FHIRString|string|null name Who contributed the content */
        #[NotBlank]
        public FHIRString|string|null $name = null,
        /** @var array<FHIRContactDetail> contact Contact details of the contributor */
        public array $contact = [],
    ) {
        parent::__construct($id, $extension);
    }
}
