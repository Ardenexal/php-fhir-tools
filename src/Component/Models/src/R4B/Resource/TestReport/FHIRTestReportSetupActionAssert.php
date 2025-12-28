<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The results of the assertion performed on the previous operations.
 */
#[FHIRBackboneElement(parentResource: 'TestReport', elementPath: 'TestReport.setup.action.assert', fhirVersion: 'R4B')]
class FHIRTestReportSetupActionAssert extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
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
        public ?\FHIRTestReportActionResultType $result = null,
        /** @var FHIRMarkdown|null message A message associated with the result */
        public ?\FHIRMarkdown $message = null,
        /** @var FHIRString|string|null detail A link to further details on the result */
        public \FHIRString|string|null $detail = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
