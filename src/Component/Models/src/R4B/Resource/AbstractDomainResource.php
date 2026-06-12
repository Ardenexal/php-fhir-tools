<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Metadata\Traits\FHIRExtensionsTrait;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\UriPrimitive;

/**
 * @author Health Level Seven International (FHIR Infrastructure)
 *
 * @see http://hl7.org/fhir/StructureDefinition/DomainResource
 *
 * @description A resource that includes narrative, extensions, and contained resources.
 */
#[FhirResource(
    type: 'DomainResource',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/DomainResource',
    fhirVersion: 'R4B',
)]
#[FHIRPathInvariant(
    key: 'dom-2',
    severity: 'error',
    expression: 'contained.contained.empty()',
    human: 'If the resource is contained in another resource, it SHALL NOT contain nested Resources',
)]
#[FHIRPathInvariant(
    key: 'dom-3',
    severity: 'error',
    expression: 'contained.where(((id.exists() and (\'#\'+id in (%resource.descendants().reference | %resource.descendants().as(canonical) | %resource.descendants().as(uri) | %resource.descendants().as(url)))) or descendants().where(reference = \'#\').exists() or descendants().where(as(canonical) = \'#\').exists() or descendants().where(as(uri) = \'#\').exists()).not()).trace(\'unmatched\', id).empty()',
    human: 'If the resource is contained in another resource, it SHALL be referred to from elsewhere in the resource or SHALL refer to the containing resource',
)]
#[FHIRPathInvariant(
    key: 'dom-4',
    severity: 'error',
    expression: 'contained.meta.versionId.empty() and contained.meta.lastUpdated.empty()',
    human: 'If a resource is contained in another resource, it SHALL NOT have a meta.versionId or a meta.lastUpdated',
)]
#[FHIRPathInvariant(
    key: 'dom-5',
    severity: 'error',
    expression: 'contained.meta.security.empty()',
    human: 'If a resource is contained in another resource, it SHALL NOT have a security label',
)]
#[FHIRPathInvariant(
    key: 'dom-6',
    severity: 'warning',
    expression: 'text.`div`.exists()',
    human: 'A resource should have narrative for robust management',
)]
abstract class AbstractDomainResource extends AbstractResource
{
    use FHIRExtensionsTrait;

    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive'), FHIRIsModifier(reason: 'This element is labeled as a modifier because the implicit rules may provide additional knowledge about the resource that modifies it\'s meaning or interpretation')]
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        #[FHIRValueSetBinding(
            valueSetUrl: 'http://hl7.org/fhir/ValueSet/languages',
            strength: 'preferred',
            maxValueSetUrl: 'http://hl7.org/fhir/ValueSet/all-languages',
        )]
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<AbstractResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the resource that contains them')]
        public array $modifierExtension = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language);
    }
}
