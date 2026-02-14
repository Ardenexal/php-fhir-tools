<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;

/**
 * @description Information about security implementation from an interface perspective - what a client needs to know.
 */
#[FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.rest.security', fhirVersion: 'R4')]
class CapabilityStatementRestSecurity extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var bool|null cors Adds CORS Headers (http://enable-cors.org/) */
        public ?bool $cors = null,
        /** @var array<CodeableConcept> service OAuth | SMART-on-FHIR | NTLM | Basic | Kerberos | Certificates */
        public array $service = [],
        /** @var MarkdownPrimitive|null description General description of how security works */
        public ?MarkdownPrimitive $description = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
