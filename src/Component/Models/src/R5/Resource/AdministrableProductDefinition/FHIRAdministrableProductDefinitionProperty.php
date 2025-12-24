<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Characteristics e.g. a product's onset of action.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'AdministrableProductDefinition',
    elementPath: 'AdministrableProductDefinition.property',
    fhirVersion: 'R5',
)]
class FHIRAdministrableProductDefinitionProperty extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null type A code expressing the type of characteristic */
        #[NotBlank]
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|FHIRQuantity|FHIRDate|FHIRBoolean|FHIRMarkdown|FHIRAttachment|FHIRReference|null valueX A value for the characteristic */
        public FHIRCodeableConcept|FHIRQuantity|FHIRDate|FHIRBoolean|FHIRMarkdown|FHIRAttachment|FHIRReference|null $valueX = null,
        /** @var FHIRCodeableConcept|null status The status of characteristic e.g. assigned or pending */
        public ?FHIRCodeableConcept $status = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
