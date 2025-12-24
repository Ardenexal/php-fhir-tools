<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;

/**
 * @description Authorization in areas within a country.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
    parentResource: 'MedicinalProductAuthorization',
    elementPath: 'MedicinalProductAuthorization.jurisdictionalAuthorization',
    fhirVersion: 'R4B',
)]
class FHIRMedicinalProductAuthorizationJurisdictionalAuthorization extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier The assigned number for the marketing authorization */
        public array $identifier = [],
        /** @var FHIRCodeableConcept|null country Country of authorization */
        public ?FHIRCodeableConcept $country = null,
        /** @var array<FHIRCodeableConcept> jurisdiction Jurisdiction within a country */
        public array $jurisdiction = [],
        /** @var FHIRCodeableConcept|null legalStatusOfSupply The legal status of supply in a jurisdiction or region */
        public ?FHIRCodeableConcept $legalStatusOfSupply = null,
        /** @var FHIRPeriod|null validityPeriod The start and expected end date of the authorization */
        public ?FHIRPeriod $validityPeriod = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
