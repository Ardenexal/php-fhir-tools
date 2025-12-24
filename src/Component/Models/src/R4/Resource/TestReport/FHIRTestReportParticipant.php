<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A participant in the test execution, either the execution engine, a client, or a server.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.participant', fhirVersion: 'R4')]
class FHIRTestReportParticipant extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRTestReportParticipantTypeType|null type test-engine | client | server */
        #[NotBlank]
        public ?FHIRTestReportParticipantTypeType $type = null,
        /** @var FHIRUri|null uri The uri of the participant. An absolute URL is preferred */
        #[NotBlank]
        public ?FHIRUri $uri = null,
        /** @var FHIRString|string|null display The display name of the participant */
        public FHIRString|string|null $display = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
