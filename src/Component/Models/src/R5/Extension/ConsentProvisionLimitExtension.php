<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\IdPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / Security
 *
 * @see http://hl7.org/fhir/StructureDefinition/consent-provision-limit
 *
 * @description Extension to express limits on the permitted use of data. This extension expresses limits to be placed on the release of data authorized by the provision in which it exists. The limits are restrictions on the permitted use. Limits may be expressed by refrain or obligation codes applied to the activity, meta.security tags to be applied to Resources, identified elements to be removed from Resources used in this activity, or data paths to be removed from Resources used in this activity.
 *
 * This extension should be used as a ModifierExtension as it is critical to the proper understanding of the consent. If the provision limit is not observed, then the consent is effectively not valid and should not be applied.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/consent-provision-limit', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'Consent.provision')]
#[FHIRExtensionContext(type: 'element', expression: 'Consent.provision.provision')]
class ConsentProvisionLimitExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var array<CodeableConcept> control Coded restriction such as a refrain or obligation. */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
        public array $control = [],
        /** @var array<CodeableConcept> tag Meta.security tags to be applied to data that is used under this provision. */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isArray: true)]
        public array $tag = [],
        /** @var array<IdPrimitive> element What data elements, by id, must be removed from the data */
        #[FhirProperty(fhirType: 'id', propertyKind: 'primitive', isArray: true)]
        public array $element = [],
        /** @var array<StringPrimitive> path What data elements, by FHIRPath, must be removed from the data */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive', isArray: true)]
        public array $path = [],
        ?string $id = null,
    ) {
        $subExtensions = [];
        foreach ($this->control as $v) {
            $subExtensions[] = new Extension(url: 'control', value: $v);
        }
        foreach ($this->tag as $v) {
            $subExtensions[] = new Extension(url: 'tag', value: $v);
        }
        foreach ($this->element as $v) {
            $subExtensions[] = new Extension(url: 'element', value: $v);
        }
        foreach ($this->path as $v) {
            $subExtensions[] = new Extension(url: 'path', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/consent-provision-limit',
        );
    }

    /**
     * Reconstruct from an array of already-denormalized sub-extension objects.
     *
     * @param array<Extension> $subExtensions
     * @param string|null      $id
     */
    public static function fromSubExtensions(array $subExtensions, ?string $id = null): self
    {
        $control = [];
        $tag     = [];
        $element = [];
        $path    = [];

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'control' && $ext->value instanceof CodeableConcept) {
                $control[] = $ext->value;
            }
            if ($extUrl === 'tag' && $ext->value instanceof CodeableConcept) {
                $tag[] = $ext->value;
            }
            if ($extUrl === 'element' && $ext->value instanceof IdPrimitive) {
                $element[] = $ext->value;
            }
            if ($extUrl === 'path' && $ext->value instanceof StringPrimitive) {
                $path[] = $ext->value;
            }
        }

        return new self($control, $tag, $element, $path, $id);
    }
}
