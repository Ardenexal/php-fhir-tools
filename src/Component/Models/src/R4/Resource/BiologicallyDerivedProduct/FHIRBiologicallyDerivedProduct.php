<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBiologicallyDerivedProductCategoryType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBiologicallyDerivedProductStatusType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;

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
class FHIRBiologicallyDerivedProduct extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier External ids for this item */
        public array $identifier = [],
        /** @var FHIRBiologicallyDerivedProductCategoryType|null productCategory organ | tissue | fluid | cells | biologicalAgent */
        public ?FHIRBiologicallyDerivedProductCategoryType $productCategory = null,
        /** @var FHIRCodeableConcept|null productCode What this biologically derived product is */
        public ?FHIRCodeableConcept $productCode = null,
        /** @var FHIRBiologicallyDerivedProductStatusType|null status available | unavailable */
        public ?FHIRBiologicallyDerivedProductStatusType $status = null,
        /** @var array<FHIRReference> request Procedure request */
        public array $request = [],
        /** @var FHIRInteger|null quantity The amount of this biologically derived product */
        public ?FHIRInteger $quantity = null,
        /** @var array<FHIRReference> parent BiologicallyDerivedProduct parent */
        public array $parent = [],
        /** @var FHIRBiologicallyDerivedProductCollection|null collection How this product was collected */
        public ?FHIRBiologicallyDerivedProductCollection $collection = null,
        /** @var array<FHIRBiologicallyDerivedProductProcessing> processing Any processing of the product during collection */
        public array $processing = [],
        /** @var FHIRBiologicallyDerivedProductManipulation|null manipulation Any manipulation of product post-collection */
        public ?FHIRBiologicallyDerivedProductManipulation $manipulation = null,
        /** @var array<FHIRBiologicallyDerivedProductStorage> storage Product storage */
        public array $storage = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
