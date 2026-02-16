<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\OperationOutcome;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\IssueSeverityType;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\IssueTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description An error, warning, or information message that results from a system action.
 */
#[FHIRBackboneElement(parentResource: 'OperationOutcome', elementPath: 'OperationOutcome.issue', fhirVersion: 'R4')]
class OperationOutcomeIssue extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var IssueSeverityType|null severity fatal | error | warning | information */
        #[NotBlank]
        public ?IssueSeverityType $severity = null,
        /** @var IssueTypeType|null code Error or warning code */
        #[NotBlank]
        public ?IssueTypeType $code = null,
        /** @var CodeableConcept|null details Additional details about the error */
        public ?CodeableConcept $details = null,
        /** @var StringPrimitive|string|null diagnostics Additional diagnostic information about the issue */
        public StringPrimitive|string|null $diagnostics = null,
        /** @var array<StringPrimitive|string> location Deprecated: Path of element(s) related to issue */
        public array $location = [],
        /** @var array<StringPrimitive|string> expression FHIRPath of element(s) related to issue */
        public array $expression = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
