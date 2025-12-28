<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductPharmaceutical
 *
 * @description A pharmaceutical product described in terms of its composition and dose form.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'MedicinalProductPharmaceutical',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductPharmaceutical',
    fhirVersion: 'R4',
)]
class FHIRMedicinalProductPharmaceutical extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier An identifier for the pharmaceutical medicinal product */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null administrableDoseForm The administrable dose form, after necessary reconstitution */
        #[NotBlank]
        public ?\FHIRCodeableConcept $administrableDoseForm = null,
        /** @var FHIRCodeableConcept|null unitOfPresentation Todo */
        public ?\FHIRCodeableConcept $unitOfPresentation = null,
        /** @var array<FHIRReference> ingredient Ingredient */
        public array $ingredient = [],
        /** @var array<FHIRReference> device Accompanying device */
        public array $device = [],
        /** @var array<FHIRMedicinalProductPharmaceuticalCharacteristics> characteristics Characteristics e.g. a products onset of action */
        public array $characteristics = [],
        /** @var array<FHIRMedicinalProductPharmaceuticalRouteOfAdministration> routeOfAdministration The path by which the pharmaceutical product is taken into or makes contact with the body */
        public array $routeOfAdministration = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
