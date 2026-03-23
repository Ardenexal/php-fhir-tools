<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExampleScenario;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @description Each step of the process.
 */
#[FHIRBackboneElement(parentResource: 'ExampleScenario', elementPath: 'ExampleScenario.process.step', fhirVersion: 'R4B')]
class ExampleScenarioProcessStep extends BackboneElement
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
        /** @var array<ExampleScenarioProcess> process Nested process */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExampleScenario\ExampleScenarioProcess',
        )]
        public array $process = [],
        /** @var bool|null pause If there is a pause in the flow */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar')]
        public ?bool $pause = null,
        /** @var ExampleScenarioProcessStepOperation|null operation Each interaction or action */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?ExampleScenarioProcessStepOperation $operation = null,
        /** @var array<ExampleScenarioProcessStepAlternative> alternative Alternate non-typical step action */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4B\Resource\ExampleScenario\ExampleScenarioProcessStepAlternative',
        )]
        public array $alternative = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
