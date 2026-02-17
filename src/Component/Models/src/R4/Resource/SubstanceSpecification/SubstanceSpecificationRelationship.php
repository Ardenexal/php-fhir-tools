<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description A link between this substance and another, with details of the relationship.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.relationship', fhirVersion: 'R4')]
class SubstanceSpecificationRelationship extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|CodeableConcept|null substanceX A pointer to another substance, as a resource or just a representational code */
        public Reference|CodeableConcept|null $substanceX = null,
        /** @var CodeableConcept|null relationship For example "salt to parent", "active moiety", "starting material" */
        public ?CodeableConcept $relationship = null,
        /** @var bool|null isDefining For example where an enzyme strongly bonds with a particular substance, this is a defining relationship for that enzyme, out of several possible substance relationships */
        public ?bool $isDefining = null,
        /** @var Quantity|Range|Ratio|StringPrimitive|string|null amountX A numeric factor for the relationship, for instance to express that the salt of a substance has some percentage of the active substance in relation to some other */
        public Quantity|Range|Ratio|StringPrimitive|string|null $amountX = null,
        /** @var Ratio|null amountRatioLowLimit For use when the numeric */
        public ?Ratio $amountRatioLowLimit = null,
        /** @var CodeableConcept|null amountType An operator for the amount, for example "average", "approximately", "less than" */
        public ?CodeableConcept $amountType = null,
        /** @var array<Reference> source Supporting literature */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
