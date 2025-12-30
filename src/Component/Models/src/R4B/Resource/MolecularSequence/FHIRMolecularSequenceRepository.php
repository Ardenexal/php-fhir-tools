<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRepositoryTypeType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Configurations of the external repository. The repository shall store target's observedSeq or records related with target's observedSeq.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.repository', fhirVersion: 'R4B')]
class FHIRMolecularSequenceRepository extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRRepositoryTypeType|null type directlink | openapi | login | oauth | other */
        #[NotBlank]
        public ?FHIRRepositoryTypeType $type = null,
        /** @var FHIRUri|null url URI of the repository */
        public ?FHIRUri $url = null,
        /** @var FHIRString|string|null name Repository's name */
        public FHIRString|string|null $name = null,
        /** @var FHIRString|string|null datasetId Id of the dataset that used to call for dataset in repository */
        public FHIRString|string|null $datasetId = null,
        /** @var FHIRString|string|null variantsetId Id of the variantset that used to call for variantset in repository */
        public FHIRString|string|null $variantsetId = null,
        /** @var FHIRString|string|null readsetId Id of the read */
        public FHIRString|string|null $readsetId = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
