<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Sponsors, collaborators, and other parties.
 */
#[FHIRBackboneElement(parentResource: 'ResearchStudy', elementPath: 'ResearchStudy.associatedParty', fhirVersion: 'R5')]
class FHIRResearchStudyAssociatedParty extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null name Name of associated party */
        public FHIRString|string|null $name = null,
        /** @var FHIRCodeableConcept|null role sponsor | lead-sponsor | sponsor-investigator | primary-investigator | collaborator | funding-source | general-contact | recruitment-contact | sub-investigator | study-director | study-chair */
        #[NotBlank]
        public ?FHIRCodeableConcept $role = null,
        /** @var array<FHIRPeriod> period When active in the role */
        public array $period = [],
        /** @var array<FHIRCodeableConcept> classifier nih | fda | government | nonprofit | academic | industry */
        public array $classifier = [],
        /** @var FHIRReference|null party Individual or organization associated with study (use practitionerRole to specify their organisation) */
        public ?FHIRReference $party = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
