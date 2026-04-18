<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ExampleScenario;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The step represents a single operation invoked on receiver by sender.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step.operation', fhirVersion: 'R5')]
class ExampleScenarioProcessStepOperation extends BackboneElement
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
        /** @var Coding|null type Kind of action */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public ?Coding $type = null,
        /** @var StringPrimitive|string|null title Label for step */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isRequired: true), NotBlank]
        public StringPrimitive|string|null $title = null,
        /** @var StringPrimitive|string|null initiator Who starts the operation */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $initiator = null,
        /** @var StringPrimitive|string|null receiver Who receives the operation */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $receiver = null,
        /** @var MarkdownPrimitive|null description Human-friendly description of the operation */
        #[FhirProperty(fhirType: 'markdown', propertyKind: 'primitive')]
        public ?MarkdownPrimitive $description = null,
        /** @var bool|null initiatorActive Initiator stays active? */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $initiatorActive = null,
        /** @var bool|null receiverActive Receiver stays active? */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $receiverActive = null,
        /** @var ExampleScenarioInstanceContainedInstance|null request Instance transmitted on invocation */
        #[FhirProperty(fhirType: 'unknown', propertyKind: 'complex')]
        public ?ExampleScenarioInstanceContainedInstance $request = null,
        /** @var ExampleScenarioInstanceContainedInstance|null response Instance transmitted on invocation response */
        #[FhirProperty(fhirType: 'unknown', propertyKind: 'complex')]
        public ?ExampleScenarioInstanceContainedInstance $response = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
