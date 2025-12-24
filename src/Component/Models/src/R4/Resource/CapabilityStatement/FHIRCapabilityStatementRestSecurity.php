<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRBoolean;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRMarkdown;

/**
 * @description Information about security implementation from an interface perspective - what a client needs to know.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest.security', fhirVersion: 'R4')]
class FHIRCapabilityStatementRestSecurity extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRBoolean|null cors Adds CORS Headers (http://enable-cors.org/) */
        public ?FHIRBoolean $cors = null,
        /** @var array<FHIRCodeableConcept> service OAuth | SMART-on-FHIR | NTLM | Basic | Kerberos | Certificates */
        public array $service = [],
        /** @var FHIRMarkdown|null description General description of how security works */
        public ?FHIRMarkdown $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
