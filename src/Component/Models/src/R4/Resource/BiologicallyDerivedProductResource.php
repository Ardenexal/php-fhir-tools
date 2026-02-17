<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BiologicallyDerivedProductCategoryType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BiologicallyDerivedProductStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BiologicallyDerivedProduct\BiologicallyDerivedProductCollection;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BiologicallyDerivedProduct\BiologicallyDerivedProductManipulation;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BiologicallyDerivedProduct\BiologicallyDerivedProductProcessing;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BiologicallyDerivedProduct\BiologicallyDerivedProductStorage;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProduct
 *
 * @description A material substance originating from a biological entity intended to be transplanted or infused
 * into another (possibly the same) biological entity.
 */
#[FhirResource(
    type: 'BiologicallyDerivedProduct',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/BiologicallyDerivedProduct',
    fhirVersion: 'R4',
)]
class BiologicallyDerivedProductResource extends DomainResourceResource
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
        /** @var array<Identifier> identifier External ids for this item */
        public array $identifier = [],
        /** @var BiologicallyDerivedProductCategoryType|null productCategory organ | tissue | fluid | cells | biologicalAgent */
        public ?BiologicallyDerivedProductCategoryType $productCategory = null,
        /** @var CodeableConcept|null productCode What this biologically derived product is */
        public ?CodeableConcept $productCode = null,
        /** @var BiologicallyDerivedProductStatusType|null status available | unavailable */
        public ?BiologicallyDerivedProductStatusType $status = null,
        /** @var array<Reference> request Procedure request */
        public array $request = [],
        /** @var int|null quantity The amount of this biologically derived product */
        public ?int $quantity = null,
        /** @var array<Reference> parent BiologicallyDerivedProduct parent */
        public array $parent = [],
        /** @var BiologicallyDerivedProductCollection|null collection How this product was collected */
        public ?BiologicallyDerivedProductCollection $collection = null,
        /** @var array<BiologicallyDerivedProductProcessing> processing Any processing of the product during collection */
        public array $processing = [],
        /** @var BiologicallyDerivedProductManipulation|null manipulation Any manipulation of product post-collection */
        public ?BiologicallyDerivedProductManipulation $manipulation = null,
        /** @var array<BiologicallyDerivedProductStorage> storage Product storage */
        public array $storage = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
