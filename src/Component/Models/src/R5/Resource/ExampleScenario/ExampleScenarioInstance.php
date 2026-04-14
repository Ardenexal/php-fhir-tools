<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ExampleScenario;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A single data collection that is shared as part of the scenario.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.instance', fhirVersion: 'R5')]
class ExampleScenarioInstance extends BackboneElement
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
        /** @var StringPrimitive|string|null key ID or acronym of the instance */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $key = null,
        /** @var Coding|null structureType Data structure for example */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?Coding $structureType = null,
        /** @var StringPrimitive|string|null structureVersion E.g. 4.0.1 */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $structureVersion = null,
        /** @var CanonicalPrimitive|UriPrimitive|null structureProfile Rules instance adheres to */
        #[FhirProperty(
            fhirType: 'choice',
            propertyKind: 'choice',
            isChoice: true,
            variants: [
                [
                    'fhirType'     => 'canonical',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\CanonicalPrimitive',
                    'jsonKey'      => 'structureProfileCanonical',
                ],
                [
                    'fhirType'     => 'uri',
                    'propertyKind' => 'primitive',
                    'phpType'      => 'Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive',
                    'jsonKey'      => 'structureProfileUri',
                ],
            ],
        )]
        public CanonicalPrimitive|UriPrimitive|null $structureProfile = null,
        /** @var StringPrimitive|string|null title Label for instance */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $title = null,
        /** @var MarkdownPrimitive|null description Human-friendly description of the instance */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var Reference|null content Example instance data */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $content = null,
        /** @var array<ExampleScenarioInstanceVersion> version Snapshot of instance that changes */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ExampleScenario\ExampleScenarioInstanceVersion',
        )]
        public array $version = [],
        /** @var array<ExampleScenarioInstanceContainedInstance> containedInstance Resources contained in the instance */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ExampleScenario\ExampleScenarioInstanceContainedInstance',
        )]
        public array $containedInstance = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
