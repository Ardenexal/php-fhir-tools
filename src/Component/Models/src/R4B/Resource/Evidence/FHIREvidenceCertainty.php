<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAnnotation;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Assessment of certainty, confidence in the estimates, or quality of the evidence.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Evidence', elementPath: 'Evidence.certainty', fhirVersion: 'R4B')]
class FHIREvidenceCertainty extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRString|string|null description Textual description of certainty */
        public FHIRString|string|null $description = null,
        /** @var array<FHIRAnnotation> note Footnotes and/or explanatory notes */
        public array $note = [],
        /** @var FHIRCodeableConcept|null type Aspect of certainty being rated */
        public ?FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null rating Assessment or judgement of the aspect */
        public ?FHIRCodeableConcept $rating = null,
        /** @var FHIRString|string|null rater Individual or group who did the rating */
        public FHIRString|string|null $rater = null,
        /** @var array<FHIREvidenceCertainty> subcomponent A domain or subdomain of certainty */
        public array $subcomponent = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
