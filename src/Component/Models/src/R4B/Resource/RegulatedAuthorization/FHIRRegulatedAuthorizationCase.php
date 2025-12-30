<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDateTime;

/**
 * @description The case or regulatory procedure for granting or amending a regulated authorization. An authorization is granted in response to submissions/applications by those seeking authorization. A case is the administrative process that deals with the application(s) that relate to this and assesses them. Note: This area is subject to ongoing review and the workgroup is seeking implementer feedback on its use (see link at bottom of page).
 */
#[FHIRBackboneElement(parentResource: 'RegulatedAuthorization', elementPath: 'RegulatedAuthorization.case', fhirVersion: 'R4B')]
class FHIRRegulatedAuthorizationCase extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Identifier by which this case can be referenced */
        public ?FHIRIdentifier $identifier = null,
        /** @var FHIRCodeableConcept|null type The defining type of case */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null status The status associated with the case */
        public ?FHIRCodeableConcept $status = null,
        /** @var FHIRPeriod|FHIRDateTime|null dateX Relevant date for this case */
        public FHIRPeriod|FHIRDateTime|null $dateX = null,
        /** @var array<FHIRRegulatedAuthorizationCase> application Applications submitted to obtain a regulated authorization. Steps within the longer running case or procedure */
        public array $application = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
