<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/MarketingStatus
 *
 * @description The marketing status describes the date when a medicinal product is actually put on the market or the date as of which it is no longer available.
 */
#[FHIRBackboneElement(parentResource: 'MarketingStatus', elementPath: 'MarketingStatus', fhirVersion: 'R4')]
class MarketingStatus extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null country The country in which the marketing authorisation has been granted shall be specified It should be specified using the ISO 3166 ‑ 1 alpha-2 code elements */
        #[NotBlank]
        public ?CodeableConcept $country = null,
        /** @var CodeableConcept|null jurisdiction Where a Medicines Regulatory Agency has granted a marketing authorisation for which specific provisions within a jurisdiction apply, the jurisdiction can be specified using an appropriate controlled terminology The controlled term and the controlled term identifier shall be specified */
        public ?CodeableConcept $jurisdiction = null,
        /** @var CodeableConcept|null status This attribute provides information on the status of the marketing of the medicinal product See ISO/TS 20443 for more information and examples */
        #[NotBlank]
        public ?CodeableConcept $status = null,
        /** @var Period|null dateRange The date when the Medicinal Product is placed on the market by the Marketing Authorisation Holder (or where applicable, the manufacturer/distributor) in a country and/or jurisdiction shall be provided A complete date consisting of day, month and year shall be specified using the ISO 8601 date format NOTE “Placed on the market” refers to the release of the Medicinal Product into the distribution chain */
        #[NotBlank]
        public ?Period $dateRange = null,
        /** @var DateTimePrimitive|null restoreDate The date when the Medicinal Product is placed on the market by the Marketing Authorisation Holder (or where applicable, the manufacturer/distributor) in a country and/or jurisdiction shall be provided A complete date consisting of day, month and year shall be specified using the ISO 8601 date format NOTE “Placed on the market” refers to the release of the Medicinal Product into the distribution chain */
        public ?DateTimePrimitive $restoreDate = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
