<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Security)
 * @see http://hl7.org/fhir/StructureDefinition/Permission
 * @description Permission resource holds access rules for a given data and context.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Permission', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Permission', fhirVersion: 'R5')]
class FHIRPermission extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPermissionStatusType status active | entered-in-error | draft | rejected */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPermissionStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference asserter The person or entity that asserts the permission */
		public ?FHIRReference $asserter = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRDateTime> date The date that permission was asserted */
		public array $date = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod validity The period in which the permission is active */
		public ?FHIRPeriod $validity = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPermissionJustification justification The asserted justification for using the data */
		public ?FHIRPermissionJustification $justification = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPermissionRuleCombiningType combining deny-overrides | permit-overrides | ordered-deny-overrides | ordered-permit-overrides | deny-unless-permit | permit-unless-deny */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRPermissionRuleCombiningType $combining = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPermissionRule> rule Constraints to the Permission */
		public array $rule = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
