<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer\SubstancePolymerMonomerSet;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstancePolymer\SubstancePolymerRepeat;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubstancePolymer
 *
 * @description Todo.
 */
#[FhirResource(
    type: 'SubstancePolymer',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/SubstancePolymer',
    fhirVersion: 'R4',
)]
class SubstancePolymerResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null class Todo */
        public ?CodeableConcept $class = null,
        /** @var CodeableConcept|null geometry Todo */
        public ?CodeableConcept $geometry = null,
        /** @var array<CodeableConcept> copolymerConnectivity Todo */
        public array $copolymerConnectivity = [],
        /** @var array<StringPrimitive|string> modification Todo */
        public array $modification = [],
        /** @var array<SubstancePolymerMonomerSet> monomerSet Todo */
        public array $monomerSet = [],
        /** @var array<SubstancePolymerRepeat> repeat Todo */
        public array $repeat = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
