<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Security)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Permission
 *
 * @description Permission resource holds access rules for a given data and context.
 */
#[FhirResource(type: 'Permission', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Permission', fhirVersion: 'R5')]
class FHIRPermission extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRPermissionStatusType|null status active | entered-in-error | draft | rejected */
        #[NotBlank]
        public ?FHIRPermissionStatusType $status = null,
        /** @var FHIRReference|null asserter The person or entity that asserts the permission */
        public ?FHIRReference $asserter = null,
        /** @var array<FHIRDateTime> date The date that permission was asserted */
        public array $date = [],
        /** @var FHIRPeriod|null validity The period in which the permission is active */
        public ?FHIRPeriod $validity = null,
        /** @var FHIRPermissionJustification|null justification The asserted justification for using the data */
        public ?FHIRPermissionJustification $justification = null,
        /** @var FHIRPermissionRuleCombiningType|null combining deny-overrides | permit-overrides | ordered-deny-overrides | ordered-permit-overrides | deny-unless-permit | permit-unless-deny */
        #[NotBlank]
        public ?FHIRPermissionRuleCombiningType $combining = null,
        /** @var array<FHIRPermissionRule> rule Constraints to the Permission */
        public array $rule = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
