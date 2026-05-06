<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\StringPrimitive;

/**
 * @author HL7 International / Orders and Observations
 *
 * @see http://hl7.org/fhir/StructureDefinition/devicerequest-patientInstruction
 *
 * @description Simple concise instructions to be read by the patient.  For example  “twice a day” rather than “BID.”.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/devicerequest-patientInstruction', fhirVersion: 'R5')]
class DRPatientInstructionExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var CodePrimitive lang Language */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public CodePrimitive $lang,
        /** @var StringPrimitive content Text */
        #[FhirProperty(fhirType: 'string', propertyKind: 'primitive')]
        public StringPrimitive $content,
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'lang', value: $this->lang);
        $subExtensions[] = new Extension(url: 'content', value: $this->content);
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/devicerequest-patientInstruction',
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
        $lang    = null;
        $content = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'lang' && $ext->value instanceof CodePrimitive) {
                $lang = $ext->value;
            }
            if ($extUrl === 'content' && $ext->value instanceof StringPrimitive) {
                $content = $ext->value;
            }
        }

        if ($lang === null) {
            throw new \InvalidArgumentException('Required sub-extension "lang" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }
        if ($content === null) {
            throw new \InvalidArgumentException('Required sub-extension "content" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($lang, $content, $id);
    }
}
