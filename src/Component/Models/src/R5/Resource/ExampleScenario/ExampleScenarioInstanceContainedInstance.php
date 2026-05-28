<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ExampleScenario;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description References to other instances that can be found within this instance (e.g. the observations contained in a bundle).
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.instance.containedInstance', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'exs-14',
    severity: 'error',
    expression: '%resource.instance.where(key=%context.instanceReference).exists()',
    human: 'InstanceReference must be a key of an instance defined in the ExampleScenario',
)]
#[FHIRPathInvariant(
    key: 'exs-15',
    severity: 'error',
    expression: 'versionReference.empty() implies %resource.instance.where(key=%context.instanceReference).version.empty()',
    human: 'versionReference must be specified if the referenced instance defines versions',
)]
#[FHIRPathInvariant(
    key: 'exs-16',
    severity: 'error',
    expression: 'versionReference.exists() implies %resource.instance.where(key=%context.instanceReference).version.where(key=%context.versionReference).exists()',
    human: 'versionReference must be a key of a version within the instance pointed to by instanceReference',
)]
class ExampleScenarioInstanceContainedInstance extends BackboneElement
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
        /** @var StringPrimitive|string|null instanceReference Key of contained instance */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $instanceReference = null,
        /** @var StringPrimitive|string|null versionReference Key of contained instance version */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $versionReference = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
