<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ExampleScenario;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ExampleScenarioActorTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A system or person who shares or receives an instance within the scenario.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.actor', fhirVersion: 'R5')]
class ExampleScenarioActor extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var StringPrimitive|string|null key ID or acronym of the actor */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $key = null,
        /** @var ExampleScenarioActorTypeType|null type person | system */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?ExampleScenarioActorTypeType $type = null,
        /** @var StringPrimitive|string|null title Label for actor when rendering */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $title = null,
        /** @var MarkdownPrimitive|null description Details about actor */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
