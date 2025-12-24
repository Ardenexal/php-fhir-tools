<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/AdministrableProductDefinition
 *
 * @description A medicinal product in the final form which is suitable for administering to a patient (after any mixing of multiple components, dissolution etc. has been performed).
 */
#[FhirResource(
    type: 'AdministrableProductDefinition',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/AdministrableProductDefinition',
    fhirVersion: 'R4B',
)]
class FHIRAdministrableProductDefinition extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier An identifier for the administrable product */
        public array $identifier = [],
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?FHIRPublicationStatusType $status = null,
        /** @var array<FHIRReference> formOf References a product from which one or more of the constituent parts of that product can be prepared and used as described by this administrable product */
        public array $formOf = [],
        /** @var FHIRCodeableConcept|null administrableDoseForm The dose form of the final product after necessary reconstitution or processing */
        public ?FHIRCodeableConcept $administrableDoseForm = null,
        /** @var FHIRCodeableConcept|null unitOfPresentation The presentation type in which this item is given to a patient. e.g. for a spray - 'puff' */
        public ?FHIRCodeableConcept $unitOfPresentation = null,
        /** @var array<FHIRReference> producedFrom Indicates the specific manufactured items that are part of the 'formOf' product that are used in the preparation of this specific administrable form */
        public array $producedFrom = [],
        /** @var array<FHIRCodeableConcept> ingredient The ingredients of this administrable medicinal product. This is only needed if the ingredients are not specified either using ManufacturedItemDefiniton, or using by incoming references from the Ingredient resource */
        public array $ingredient = [],
        /** @var FHIRReference|null device A device that is integral to the medicinal product, in effect being considered as an "ingredient" of the medicinal product */
        public ?FHIRReference $device = null,
        /** @var array<FHIRAdministrableProductDefinitionProperty> property Characteristics e.g. a product's onset of action */
        public array $property = [],
        /** @var array<FHIRAdministrableProductDefinitionRouteOfAdministration> routeOfAdministration The path by which the product is taken into or makes contact with the body */
        public array $routeOfAdministration = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
