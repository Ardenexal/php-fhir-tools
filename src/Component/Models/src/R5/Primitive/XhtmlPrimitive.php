<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Primitive;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Element;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/xhtml
 *
 * @description XHTML
 */
#[FHIRPrimitive(primitiveType: 'xhtml', fhirVersion: 'R5')]
class XhtmlPrimitive extends Element
{
    public function __construct(
        /** @var string|null id xml:id (or equivalent in JSON) */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var string|null value Actual xhtml */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', isRequired: true), NotBlank]
        public ?string $value = null,
    ) {
        parent::__construct($id);
    }
}
