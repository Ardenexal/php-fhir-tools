<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\TestScript;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Capabilities that must exist and are assumed to function correctly on the FHIR server being tested.
 */
#[FHIRBackboneElement(parentResource: 'TestScript', elementPath: 'TestScript.metadata.capability', fhirVersion: 'R4')]
class TestScriptMetadataCapability extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var bool|null required Are the capabilities required? */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?bool $required = null,
        /** @var bool|null validated Are the capabilities validated? */
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?bool $validated = null,
        /** @var StringPrimitive|string|null description The expected capabilities of the server */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var array<int> origin Which origin server these requirements apply to */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
        public array $origin = [],
        /** @var int|null destination Which server these requirements apply to */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $destination = null,
        /** @var array<UriPrimitive> link Links to the FHIR specification */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isArray: true)]
        public array $link = [],
        /** @var CanonicalPrimitive|null capabilities Required Capability Statement */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?CanonicalPrimitive $capabilities = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
