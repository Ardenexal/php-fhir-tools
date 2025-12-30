<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/RegulatedAuthorization
 *
 * @description Regulatory approval, clearance or licencing related to a regulated product, treatment, facility or activity that is cited in a guidance, regulation, rule or legislative act. An example is Market Authorization relating to a Medicinal Product.
 */
#[FhirResource(
    type: 'RegulatedAuthorization',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/RegulatedAuthorization',
    fhirVersion: 'R4B',
)]
class FHIRRegulatedAuthorization extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business identifier for the authorization, typically assigned by the authorizing body */
        public array $identifier = [],
        /** @var array<FHIRReference> subject The product type, treatment, facility or activity that is being authorized */
        public array $subject = [],
        /** @var FHIRCodeableConcept|null type Overall type of this authorization, for example drug marketing approval, orphan drug designation */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRMarkdown|null description General textual supporting information */
        public ?FHIRMarkdown $description = null,
        /** @var array<FHIRCodeableConcept> region The territory in which the authorization has been granted */
        public array $region = [],
        /** @var FHIRCodeableConcept|null status The status that is authorised e.g. approved. Intermediate states can be tracked with cases and applications */
        public ?FHIRCodeableConcept $status = null,
        /** @var FHIRDateTime|null statusDate The date at which the current status was assigned */
        public ?FHIRDateTime $statusDate = null,
        /** @var FHIRPeriod|null validityPeriod The time period in which the regulatory approval etc. is in effect, e.g. a Marketing Authorization includes the date of authorization and/or expiration date */
        public ?FHIRPeriod $validityPeriod = null,
        /** @var FHIRCodeableReference|null indication Condition for which the use of the regulated product applies */
        public ?FHIRCodeableReference $indication = null,
        /** @var FHIRCodeableConcept|null intendedUse The intended use of the product, e.g. prevention, treatment */
        public ?FHIRCodeableConcept $intendedUse = null,
        /** @var array<FHIRCodeableConcept> basis The legal/regulatory framework or reasons under which this authorization is granted */
        public array $basis = [],
        /** @var FHIRReference|null holder The organization that has been granted this authorization, by the regulator */
        public ?FHIRReference $holder = null,
        /** @var FHIRReference|null regulator The regulatory authority or authorizing body granting the authorization */
        public ?FHIRReference $regulator = null,
        /** @var FHIRRegulatedAuthorizationCase|null case The case or regulatory procedure for granting or amending a regulated authorization. Note: This area is subject to ongoing review and the workgroup is seeking implementer feedback on its use (see link at bottom of page) */
        public ?FHIRRegulatedAuthorizationCase $case = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
