<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/codesystem-alternate
 *
 * @description An additional code that may be used to represent the concept.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/codesystem-alternate', fhirVersion: 'R4B')]
class AlternateExtension extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodePrimitive code Code that represents the concept */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive $code,
        /** @var Coding use Expected use of the code */
        #[FhirProperty(fhirType: 'Coding', propertyKind: 'complex')]
        public \Ardenexal\FHIRTools\Component\Models\R4B\DataType\Coding $use,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'code', value: $this->code);
        $subExtensions[] = new Extension(url: 'use', value: $this->use);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/codesystem-alternate',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<FHIRExtensionInterface> $subExtensions
     * @param string|null                   $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): static
    {
        $code = null;
        $use  = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'code' && $ext->value instanceof CodePrimitive) {
                $code = $ext->value;
            }
            if ($extUrl === 'use' && $ext->value instanceof Coding) {
                $use = $ext->value;
            }
        }

        return new static($code, $use, $id);
    }
}
