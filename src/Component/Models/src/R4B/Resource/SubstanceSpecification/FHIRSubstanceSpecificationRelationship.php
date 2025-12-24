<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRatio;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description A link between this substance and another, with details of the relationship.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.relationship', fhirVersion: 'R4B')]
class FHIRSubstanceSpecificationRelationship extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|FHIRCodeableConcept|null substanceX A pointer to another substance, as a resource or just a representational code */
        public FHIRReference|FHIRCodeableConcept|null $substanceX = null,
        /** @var FHIRCodeableConcept|null relationship For example "salt to parent", "active moiety", "starting material" */
        public ?FHIRCodeableConcept $relationship = null,
        /** @var FHIRBoolean|null isDefining For example where an enzyme strongly bonds with a particular substance, this is a defining relationship for that enzyme, out of several possible substance relationships */
        public ?FHIRBoolean $isDefining = null,
        /** @var FHIRQuantity|FHIRRange|FHIRRatio|FHIRString|string|null amountX A numeric factor for the relationship, for instance to express that the salt of a substance has some percentage of the active substance in relation to some other */
        public FHIRQuantity|FHIRRange|FHIRRatio|FHIRString|string|null $amountX = null,
        /** @var FHIRRatio|null amountRatioLowLimit For use when the numeric */
        public ?FHIRRatio $amountRatioLowLimit = null,
        /** @var FHIRCodeableConcept|null amountType An operator for the amount, for example "average", "approximately", "less than" */
        public ?FHIRCodeableConcept $amountType = null,
        /** @var array<FHIRReference> source Supporting literature */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
