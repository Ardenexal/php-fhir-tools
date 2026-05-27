<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CanonicalPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\StringPrimitive;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Binds to a value set if this element is coded (code, Coding, CodeableConcept, Quantity), or the data types (string, uri).
 */
#[FHIRComplexType(typeName: 'ElementDefinition.binding', fhirVersion: 'R4B')]
#[FHIRPathInvariant(
    key: 'eld-12',
    severity: 'error',
    expression: 'valueSet.exists() implies (valueSet.startsWith(\'http:\') or valueSet.startsWith(\'https\') or valueSet.startsWith(\'urn:\') or valueSet.startsWith(\'#\'))',
    human: 'ValueSet SHALL start with http:// or https:// or urn:',
)]
class ElementDefinitionBinding extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var BindingStrengthType|null strength required | extensible | preferred | example */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank, FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/binding-strength|4.3.0', strength: 'required')]
        public ?BindingStrengthType $strength = null,
        /** @var StringPrimitive|string|null description Human explanation of the value set */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive|string|null $description = null,
        /** @var CanonicalPrimitive|null valueSet Source of value set */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive'), FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/ValueSet'])]
        public ?CanonicalPrimitive $valueSet = null,
    ) {
        parent::__construct($id, $extension);
    }
}
