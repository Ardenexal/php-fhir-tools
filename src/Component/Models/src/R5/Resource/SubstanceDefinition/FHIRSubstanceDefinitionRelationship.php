<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A link between this substance and another, with details of the relationship.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceDefinition', elementPath: 'SubstanceDefinition.relationship', fhirVersion: 'R5')]
class FHIRSubstanceDefinitionRelationship extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|FHIRCodeableConcept|null substanceDefinitionX A pointer to another substance, as a resource or a representational code */
        public FHIRReference|FHIRCodeableConcept|null $substanceDefinitionX = null,
        /** @var FHIRCodeableConcept|null type For example "salt to parent", "active moiety" */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRBoolean|null isDefining For example where an enzyme strongly bonds with a particular substance, this is a defining relationship for that enzyme, out of several possible relationships */
        public ?FHIRBoolean $isDefining = null,
        /** @var FHIRQuantity|FHIRRatio|FHIRString|string|null amountX A numeric factor for the relationship, e.g. that a substance salt has some percentage of active substance in relation to some other */
        public FHIRQuantity|FHIRRatio|FHIRString|string|null $amountX = null,
        /** @var FHIRRatio|null ratioHighLimitAmount For use when the numeric has an uncertain range */
        public ?FHIRRatio $ratioHighLimitAmount = null,
        /** @var FHIRCodeableConcept|null comparator An operator for the amount, for example "average", "approximately", "less than" */
        public ?FHIRCodeableConcept $comparator = null,
        /** @var array<FHIRReference> source Supporting literature */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
