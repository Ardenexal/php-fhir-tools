<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/DiagnosticReport-geneticsReferences
 *
 * @description Additional bibliographic reference information about genetics, medications, clinical trials, etc. associated with knowledge-based information on genetics/genetic condition.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport-geneticsReferences', fhirVersion: 'R5')]
class ReferencesExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var StringPrimitive|null description Reference description */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public ?StringPrimitive $description = null,
        /** @var array<UriPrimitive> reference Reference URI */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive', isArray: true)]
        public array $reference = [],
        /** @var CodeableConcept|null type Reference type */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $type = null,
        ?string $id = null,
    ) {
        $subExtensions = [];
        if ($this->description !== null) {
            $subExtensions[] = new Extension(url: 'description', value: $this->description);
        }
        foreach ($this->reference as $v) {
            $subExtensions[] = new Extension(url: 'reference', value: $v);
        }
        if ($this->type !== null) {
            $subExtensions[] = new Extension(url: 'type', value: $this->type);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/DiagnosticReport-geneticsReferences',
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
        $description = null;
        $reference   = [];
        $type        = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'description' && $ext->value instanceof StringPrimitive) {
                $description = $ext->value;
            }
            if ($extUrl === 'reference' && $ext->value instanceof UriPrimitive) {
                $reference[] = $ext->value;
            }
            if ($extUrl === 'type' && $ext->value instanceof CodeableConcept) {
                $type = $ext->value;
            }
        }

        return new static($description, $reference, $type, $id);
    }
}
