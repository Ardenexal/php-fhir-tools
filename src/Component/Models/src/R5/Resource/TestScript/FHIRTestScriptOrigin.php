<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRInteger;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An abstract server used in operations within this test script in the origin element.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.origin', fhirVersion: 'R5')]
class FHIRTestScriptOrigin extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRInteger|null index The index of the abstract origin server starting at 1 */
        #[NotBlank]
        public ?FHIRInteger $index = null,
        /** @var FHIRCoding|null profile FHIR-Client | FHIR-SDC-FormFiller */
        #[NotBlank]
        public ?FHIRCoding $profile = null,
        /** @var FHIRUrl|null url The url path of the origin server */
        public ?FHIRUrl $url = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
