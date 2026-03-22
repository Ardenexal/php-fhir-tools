<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource\TestReport;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension;

/**
 * @description Action would contain either an operation or an assertion.
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.test.action', fhirVersion: 'R4B')]
class TestReportTestAction extends BackboneElement
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
        /** @var TestReportSetupActionOperation|null operation The operation performed */
        #[FhirProperty(fhirType: 'unknown', propertyKind: 'complex')]
        public ?TestReportSetupActionOperation $operation = null,
        /** @var TestReportSetupActionAssert|null assert The assertion performed */
        #[FhirProperty(fhirType: 'unknown', propertyKind: 'complex')]
        public ?TestReportSetupActionAssert $assert = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
