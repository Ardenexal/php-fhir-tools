<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\TestScript;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @description Action would contain either an operation or an assertion.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.setup.action', fhirVersion: 'R4B')]
class TestScriptSetupAction extends BackboneElement
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
        /** @var TestScriptSetupActionOperation|null operation The setup operation to perform */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?TestScriptSetupActionOperation $operation = null,
        /** @var TestScriptSetupActionAssert|null assert The assertion to perform */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?TestScriptSetupActionAssert $assert = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
