<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates an action that has been taken or is committed to reduce or eliminate the likelihood of the risk identified by the detected issue from manifesting.  Can also reflect an observation of known mitigating factors that may reduce/eliminate the need for any action.
 */
#[FHIRBackboneElement(parentResource: 'DetectedIssue', elementPath: 'DetectedIssue.mitigation', fhirVersion: 'R4')]
class FHIRDetectedIssueMitigation extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null action What mitigation? */
        #[NotBlank]
        public ?\FHIRCodeableConcept $action = null,
        /** @var FHIRDateTime|null date Date committed */
        public ?\FHIRDateTime $date = null,
        /** @var FHIRReference|null author Who is committing? */
        public ?\FHIRReference $author = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
