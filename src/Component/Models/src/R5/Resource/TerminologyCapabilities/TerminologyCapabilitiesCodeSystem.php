<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\TerminologyCapabilities;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeSystemContentModeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Identifies a code system that is supported by the server. If there is a no code system URL, then this declares the general assumptions a client can make about support for any CodeSystem resource.
 */
#[FHIRBackboneElement(parentResource: 'TerminologyCapabilities', elementPath: 'TerminologyCapabilities.codeSystem', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'tcp-1',
    severity: 'error',
    expression: 'version.count() > 1 implies version.all(code.exists())',
    human: 'If there is more than one version, a version code must be defined',
)]
#[FHIRPathInvariant(
    key: 'tcp-7',
    severity: 'error',
    expression: 'version.code.isDistinct()',
    human: 'Each version.code element must be distinct for a particular code system.',
)]
#[FHIRPathInvariant(
    key: 'tcp-8',
    severity: 'error',
    expression: 'version.where(isDefault = true).count() <= 1',
    human: 'A codeSystem element instance may have at most one version.isDefault element with a value of \'true\'.',
)]
class TerminologyCapabilitiesCodeSystem extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var CanonicalPrimitive|null uri Canonical identifier for the code system, represented as a URI */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/CodeSystem'])]
        public ?CanonicalPrimitive $uri = null,
        /** @var array<TerminologyCapabilitiesCodeSystemVersion> version Version of Code System supported */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\TerminologyCapabilities\TerminologyCapabilitiesCodeSystemVersion',
        )]
        public array $version = [],
        /** @var CodeSystemContentModeType|null content not-present | example | fragment | complete | supplement */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/codesystem-content-mode|5.0.0', strength: 'required')]
        public ?CodeSystemContentModeType $content = null,
        /** @var bool|null subsumption Whether subsumption is supported */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $subsumption = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
