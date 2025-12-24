<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRAttachment;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The artifact related to the cited artifact.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.relatesTo', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactRelatesTo extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null relationshipType How the cited artifact relates to the target artifact */
        #[NotBlank]
        public ?FHIRCodeableConcept $relationshipType = null,
        /** @var array<FHIRCodeableConcept> targetClassifier The clasification of the related artifact */
        public array $targetClassifier = [],
        /** @var FHIRUri|FHIRIdentifier|FHIRReference|FHIRAttachment|null targetX The article or artifact that the cited artifact is related to */
        #[NotBlank]
        public FHIRUri|FHIRIdentifier|FHIRReference|FHIRAttachment|null $targetX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
