<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestReport;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\TestReportActionResultType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The operation performed.
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.setup.action.operation', fhirVersion: 'R4')]
class TestReportSetupActionOperation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var TestReportActionResultType|null result pass | skip | fail | warning | error */
        #[NotBlank]
        public ?TestReportActionResultType $result = null,
        /** @var MarkdownPrimitive|null message A message associated with the result */
        public ?MarkdownPrimitive $message = null,
        /** @var UriPrimitive|null detail A link to further details on the result */
        public ?UriPrimitive $detail = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
