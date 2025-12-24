<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCanonical;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The scope indicates a conformance artifact that is tested by the test(s) within this test case and the expectation of the test outcome(s) as well as the intended test phase inclusion.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.scope', fhirVersion: 'R5')]
class FHIRTestScriptScope extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCanonical|null artifact The specific conformance artifact being tested */
        #[NotBlank]
        public ?FHIRCanonical $artifact = null,
        /** @var FHIRCodeableConcept|null conformance required | optional | strict */
        public ?FHIRCodeableConcept $conformance = null,
        /** @var FHIRCodeableConcept|null phase unit | integration | production */
        public ?FHIRCodeableConcept $phase = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
