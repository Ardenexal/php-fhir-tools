<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\PermissionRuleCombiningType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\PermissionStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Permission\PermissionJustification;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Permission\PermissionRule;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Security)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Permission
 *
 * @description Permission resource holds access rules for a given data and context.
 */
#[FhirResource(type: 'Permission', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Permission', fhirVersion: 'R5')]
class PermissionResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var PermissionStatusType|null status active | entered-in-error | draft | rejected */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?PermissionStatusType $status = null,
        /** @var Reference|null asserter The person or entity that asserts the permission */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $asserter = null,
        /** @var array<DateTimePrimitive> date The date that permission was asserted */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive', isArray: true)]
        public array $date = [],
        /** @var Period|null validity The period in which the permission is active */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $validity = null,
        /** @var PermissionJustification|null justification The asserted justification for using the data */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?PermissionJustification $justification = null,
        /** @var PermissionRuleCombiningType|null combining deny-overrides | permit-overrides | ordered-deny-overrides | ordered-permit-overrides | deny-unless-permit | permit-unless-deny */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?PermissionRuleCombiningType $combining = null,
        /** @var array<PermissionRule> rule Constraints to the Permission */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Permission\PermissionRule',
        )]
        public array $rule = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
