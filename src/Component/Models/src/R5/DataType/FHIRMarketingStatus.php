<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/MarketingStatus
 *
 * @description The marketing status describes the date when a medicinal product is actually put on the market or the date as of which it is no longer available.
 */
#[FHIRComplexType(typeName: 'MarketingStatus', fhirVersion: 'R5')]
class FHIRMarketingStatus extends FHIRBackboneType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null country The country in which the marketing authorization has been granted shall be specified It should be specified using the ISO 3166 ‑ 1 alpha-2 code elements */
        public ?\FHIRCodeableConcept $country = null,
        /** @var FHIRCodeableConcept|null jurisdiction Where a Medicines Regulatory Agency has granted a marketing authorization for which specific provisions within a jurisdiction apply, the jurisdiction can be specified using an appropriate controlled terminology The controlled term and the controlled term identifier shall be specified */
        public ?\FHIRCodeableConcept $jurisdiction = null,
        /** @var FHIRCodeableConcept|null status This attribute provides information on the status of the marketing of the medicinal product See ISO/TS 20443 for more information and examples */
        #[NotBlank]
        public ?\FHIRCodeableConcept $status = null,
        /** @var FHIRPeriod|null dateRange The date when the Medicinal Product is placed on the market by the Marketing Authorization Holder (or where applicable, the manufacturer/distributor) in a country and/or jurisdiction shall be provided A complete date consisting of day, month and year shall be specified using the ISO 8601 date format NOTE “Placed on the market” refers to the release of the Medicinal Product into the distribution chain */
        public ?\FHIRPeriod $dateRange = null,
        /** @var FHIRDateTime|null restoreDate The date when the Medicinal Product is placed on the market by the Marketing Authorization Holder (or where applicable, the manufacturer/distributor) in a country and/or jurisdiction shall be provided A complete date consisting of day, month and year shall be specified using the ISO 8601 date format NOTE “Placed on the market” refers to the release of the Medicinal Product into the distribution chain */
        public ?\FHIRDateTime $restoreDate = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
