<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRResourceTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRGraphDefinitionLink;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRGraphDefinitionLinkTargetCompartment;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Potential target for the link.
 */
#[FHIRBackboneElement(parentResource: 'GraphDefinition', elementPath: 'GraphDefinition.link.target', fhirVersion: 'R4B')]
class FHIRGraphDefinitionLinkTarget extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRResourceTypeType|null type Type of resource this link refers to */
        #[NotBlank]
        public ?FHIRResourceTypeType $type = null,
        /** @var FHIRString|string|null params Criteria for reverse lookup */
        public FHIRString|string|null $params = null,
        /** @var FHIRCanonical|null profile Profile for the target resource */
        public ?FHIRCanonical $profile = null,
        /** @var array<FHIRGraphDefinitionLinkTargetCompartment> compartment Compartment Consistency Rules */
        public array $compartment = [],
        /** @var array<FHIRGraphDefinitionLink> link Additional links from target resource */
        public array $link = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
