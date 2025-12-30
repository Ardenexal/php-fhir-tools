<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIssueSeverityType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIssueTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An error, warning, or information message that results from a system action.
 */
#[FHIRBackboneElement(parentResource: 'OperationOutcome', elementPath: 'OperationOutcome.issue', fhirVersion: 'R4B')]
class FHIROperationOutcomeIssue extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIssueSeverityType|null severity fatal | error | warning | information */
        #[NotBlank]
        public ?FHIRIssueSeverityType $severity = null,
        /** @var FHIRIssueTypeType|null code Error or warning code */
        #[NotBlank]
        public ?FHIRIssueTypeType $code = null,
        /** @var FHIRCodeableConcept|null details Additional details about the error */
        public ?FHIRCodeableConcept $details = null,
        /** @var FHIRString|string|null diagnostics Additional diagnostic information about the issue */
        public FHIRString|string|null $diagnostics = null,
        /** @var array<FHIRString|string> location Deprecated: Path of element(s) related to issue */
        public array $location = [],
        /** @var array<FHIRString|string> expression FHIRPath of element(s) related to issue */
        public array $expression = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
