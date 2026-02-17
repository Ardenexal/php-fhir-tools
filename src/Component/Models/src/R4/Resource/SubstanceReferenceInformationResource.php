<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation\SubstanceReferenceInformationClassification;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation\SubstanceReferenceInformationGene;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation\SubstanceReferenceInformationGeneElement;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation\SubstanceReferenceInformationTarget;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubstanceReferenceInformation
 *
 * @description Todo.
 */
#[FhirResource(
    type: 'SubstanceReferenceInformation',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/SubstanceReferenceInformation',
    fhirVersion: 'R4',
)]
class SubstanceReferenceInformationResource extends DomainResourceResource
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
        /** @var StringPrimitive|string|null comment Todo */
        public StringPrimitive|string|null $comment = null,
        /** @var array<SubstanceReferenceInformationGene> gene Todo */
        public array $gene = [],
        /** @var array<SubstanceReferenceInformationGeneElement> geneElement Todo */
        public array $geneElement = [],
        /** @var array<SubstanceReferenceInformationClassification> classification Todo */
        public array $classification = [],
        /** @var array<SubstanceReferenceInformationTarget> target Todo */
        public array $target = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
