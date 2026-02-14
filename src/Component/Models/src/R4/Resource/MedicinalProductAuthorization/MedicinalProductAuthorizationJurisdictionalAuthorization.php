<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductAuthorization;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Period;

/**
 * @description Authorization in areas within a country.
 */
#[FHIRBackboneElement(
    parentResource: 'MedicinalProductAuthorization',
    elementPath: 'MedicinalProductAuthorization.jurisdictionalAuthorization',
    fhirVersion: 'R4',
)]
class MedicinalProductAuthorizationJurisdictionalAuthorization extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier The assigned number for the marketing authorization */
        public array $identifier = [],
        /** @var CodeableConcept|null country Country of authorization */
        public ?CodeableConcept $country = null,
        /** @var array<CodeableConcept> jurisdiction Jurisdiction within a country */
        public array $jurisdiction = [],
        /** @var CodeableConcept|null legalStatusOfSupply The legal status of supply in a jurisdiction or region */
        public ?CodeableConcept $legalStatusOfSupply = null,
        /** @var Period|null validityPeriod The start and expected end date of the authorization */
        public ?Period $validityPeriod = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
