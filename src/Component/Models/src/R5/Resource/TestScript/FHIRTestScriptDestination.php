<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An abstract server used in operations within this test script in the destination element.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.destination', fhirVersion: 'R5')]
class FHIRTestScriptDestination extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRInteger|null index The index of the abstract destination server starting at 1 */
        #[NotBlank]
        public ?\FHIRInteger $index = null,
        /** @var FHIRCoding|null profile FHIR-Server | FHIR-SDC-FormManager | FHIR-SDC-FormReceiver | FHIR-SDC-FormProcessor */
        #[NotBlank]
        public ?\FHIRCoding $profile = null,
        /** @var FHIRUrl|null url The url path of the destination server */
        public ?\FHIRUrl $url = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
