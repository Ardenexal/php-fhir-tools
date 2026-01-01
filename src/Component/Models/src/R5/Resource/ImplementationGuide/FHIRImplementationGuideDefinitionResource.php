<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRFHIRVersionType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A resource that is part of the implementation guide. Conformance resources (value set, structure definition, capability statements etc.) are obvious candidates for inclusion, but any kind of resource can be included as an example resource.
 */
#[FHIRBackboneElement(parentResource: 'ImplementationGuide', elementPath: 'ImplementationGuide.definition.resource', fhirVersion: 'R5')]
class FHIRImplementationGuideDefinitionResource extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null reference Location of the resource */
        #[NotBlank]
        public ?FHIRReference $reference = null,
        /** @var array<FHIRFHIRVersionType> fhirVersion Versions this applies to (if different to IG) */
        public array $fhirVersion = [],
        /** @var FHIRString|string|null name Human readable name for the resource */
        public FHIRString|string|null $name = null,
        /** @var FHIRMarkdown|null description Reason why included in guide */
        public ?FHIRMarkdown $description = null,
        /** @var FHIRBoolean|null isExample Is this an example */
        public ?FHIRBoolean $isExample = null,
        /** @var array<FHIRCanonical> profile Profile(s) this is an example of */
        public array $profile = [],
        /** @var FHIRId|null groupingId Grouping this is part of */
        public ?FHIRId $groupingId = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
