<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRTestReportActionResultType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRMarkdown;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The operation performed.
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.setup.action.operation', fhirVersion: 'R5')]
class FHIRTestReportSetupActionOperation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRTestReportActionResultType|null result pass | skip | fail | warning | error */
        #[NotBlank]
        public ?FHIRTestReportActionResultType $result = null,
        /** @var FHIRMarkdown|null message A message associated with the result */
        public ?FHIRMarkdown $message = null,
        /** @var FHIRUri|null detail A link to further details on the result */
        public ?FHIRUri $detail = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
