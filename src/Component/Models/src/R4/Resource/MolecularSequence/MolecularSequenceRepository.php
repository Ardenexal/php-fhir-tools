<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MolecularSequence;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\RepositoryTypeType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Configurations of the external repository. The repository shall store target's observedSeq or records related with target's observedSeq.
 */
#[FHIRBackboneElement(parentResource: 'MolecularSequence', elementPath: 'MolecularSequence.repository', fhirVersion: 'R4')]
class MolecularSequenceRepository extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var RepositoryTypeType|null type directlink | openapi | login | oauth | other */
        #[NotBlank]
        public ?RepositoryTypeType $type = null,
        /** @var UriPrimitive|null url URI of the repository */
        public ?UriPrimitive $url = null,
        /** @var StringPrimitive|string|null name Repository's name */
        public StringPrimitive|string|null $name = null,
        /** @var StringPrimitive|string|null datasetId Id of the dataset that used to call for dataset in repository */
        public StringPrimitive|string|null $datasetId = null,
        /** @var StringPrimitive|string|null variantsetId Id of the variantset that used to call for variantset in repository */
        public StringPrimitive|string|null $variantsetId = null,
        /** @var StringPrimitive|string|null readsetId Id of the read */
        public StringPrimitive|string|null $readsetId = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
