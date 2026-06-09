<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRContextInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/questionnaire-unitValueSet
 *
 * @description A set of units that the user may choose when providing a quantity value.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-unitValueSet', fhirVersion: 'R4')]
#[FHIRExtensionContext(type: 'element', expression: 'Questionnaire.item')]
#[FHIRExtensionContext(type: 'element', expression: 'ElementDefinition')]
#[FHIRContextInvariant(expression: 'ofType(ElementDefinition).type.exists(code=\'Quantity\') or where(%resource.is(Questionnaire)).exists(type.first()=\'quantity\')')]
class QRUnitValueSetExtension extends Extension
{
    /**
     * @param list<Extension> $extension
     */
    public function __construct(
        /** @var CanonicalPrimitive|null valueCanonical Value of extension */
        #[FhirProperty(fhirType: 'canonical', propertyKind: 'primitive')]
        public ?CanonicalPrimitive $valueCanonical = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-unitValueSet',
            value: $this->valueCanonical,
        );
    }
}
