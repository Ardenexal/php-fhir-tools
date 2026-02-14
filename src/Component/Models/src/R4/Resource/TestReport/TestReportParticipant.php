<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TestReportParticipantTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A participant in the test execution, either the execution engine, a client, or a server.
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.participant', fhirVersion: 'R4')]
class TestReportParticipant extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var TestReportParticipantTypeType|null type test-engine | client | server */
        #[NotBlank]
        public ?TestReportParticipantTypeType $type = null,
        /** @var UriPrimitive|null uri The uri of the participant. An absolute URL is preferred */
        #[NotBlank]
        public ?UriPrimitive $uri = null,
        /** @var StringPrimitive|string|null display The display name of the participant */
        public StringPrimitive|string|null $display = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
