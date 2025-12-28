<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/ChargeItemDefinition
 *
 * @description The ChargeItemDefinition resource provides the properties that apply to the (billing) codes necessary to calculate costs and prices. The properties may differ largely depending on type and realm, therefore this resource gives only a rough structure and requires profiling for each type of billing code system.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'ChargeItemDefinition',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/ChargeItemDefinition',
    fhirVersion: 'R5',
)]
class FHIRChargeItemDefinition extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRUri|null url Canonical identifier for this charge item definition, represented as a URI (globally unique) */
        public ?\FHIRUri $url = null,
        /** @var array<FHIRIdentifier> identifier Additional identifier for the charge item definition */
        public array $identifier = [],
        /** @var FHIRString|string|null version Business version of the charge item definition */
        public \FHIRString|string|null $version = null,
        /** @var FHIRString|string|FHIRCoding|null versionAlgorithmX How to compare versions */
        public \FHIRString|string|\FHIRCoding|null $versionAlgorithmX = null,
        /** @var FHIRString|string|null name Name for this charge item definition (computer friendly) */
        public \FHIRString|string|null $name = null,
        /** @var FHIRString|string|null title Name for this charge item definition (human friendly) */
        public \FHIRString|string|null $title = null,
        /** @var array<FHIRUri> derivedFromUri Underlying externally-defined charge item definition */
        public array $derivedFromUri = [],
        /** @var array<FHIRCanonical> partOf A larger definition of which this particular definition is a component or step */
        public array $partOf = [],
        /** @var array<FHIRCanonical> replaces Completed or terminated request(s) whose function is taken by this new request */
        public array $replaces = [],
        /** @var FHIRPublicationStatusType|null status draft | active | retired | unknown */
        #[NotBlank]
        public ?\FHIRPublicationStatusType $status = null,
        /** @var FHIRBoolean|null experimental For testing purposes, not real usage */
        public ?\FHIRBoolean $experimental = null,
        /** @var FHIRDateTime|null date Date last changed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRString|string|null publisher Name of the publisher/steward (organization or individual) */
        public \FHIRString|string|null $publisher = null,
        /** @var array<FHIRContactDetail> contact Contact details for the publisher */
        public array $contact = [],
        /** @var FHIRMarkdown|null description Natural language description of the charge item definition */
        public ?\FHIRMarkdown $description = null,
        /** @var array<FHIRUsageContext> useContext The context that the content is intended to support */
        public array $useContext = [],
        /** @var array<FHIRCodeableConcept> jurisdiction Intended jurisdiction for charge item definition (if applicable) */
        public array $jurisdiction = [],
        /** @var FHIRMarkdown|null purpose Why this charge item definition is defined */
        public ?\FHIRMarkdown $purpose = null,
        /** @var FHIRMarkdown|null copyright Use and/or publishing restrictions */
        public ?\FHIRMarkdown $copyright = null,
        /** @var FHIRString|string|null copyrightLabel Copyright holder and year(s) */
        public \FHIRString|string|null $copyrightLabel = null,
        /** @var FHIRDate|null approvalDate When the charge item definition was approved by publisher */
        public ?\FHIRDate $approvalDate = null,
        /** @var FHIRDate|null lastReviewDate When the charge item definition was last reviewed by the publisher */
        public ?\FHIRDate $lastReviewDate = null,
        /** @var FHIRCodeableConcept|null code Billing code or product type this definition applies to */
        public ?\FHIRCodeableConcept $code = null,
        /** @var array<FHIRReference> instance Instances this definition applies to */
        public array $instance = [],
        /** @var array<FHIRChargeItemDefinitionApplicability> applicability Whether or not the billing code is applicable */
        public array $applicability = [],
        /** @var array<FHIRChargeItemDefinitionPropertyGroup> propertyGroup Group of properties which are applicable under the same conditions */
        public array $propertyGroup = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
