<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRComplexExtensionInterface;
use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Expression;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/questionnaire-optionRestriction
 *
 * @description Allows disabling certain questionnaire options for the containing item based on evaluating expressions.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-optionRestriction', fhirVersion: 'R5')]
class QOptionRestrictionExtension extends Extension implements FHIRComplexExtensionInterface
{
    public function __construct(
        /** @var Expression expression Expression to trigger exclusion */
        #[FhirProperty(fhirType: 'Expression', propertyKind: 'complex')]
        public Expression $expression,
        /** @var array<int> option Option to exclude */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar', isArray: true)]
        public array $option = [],
        ?string $id = null,
    ) {
        $subExtensions   = [];
        $subExtensions[] = new Extension(url: 'expression', value: $this->expression);
        foreach ($this->option as $v) {
            $subExtensions[] = new Extension(url: 'option', value: $v);
        }
        parent::__construct(
            id: $id,
            extension: $subExtensions,
            url: 'http://hl7.org/fhir/StructureDefinition/questionnaire-optionRestriction',
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
        $option     = [];
        $expression = null;

        foreach ($subExtensions as $ext) {
            $extUrl = $ext->getExtensionUrl();
            if ($extUrl === 'option' && is_int($ext->value)) {
                $option[] = $ext->value;
            }
            if ($extUrl === 'expression' && $ext->value instanceof Expression) {
                $expression = $ext->value;
            }
        }

        if ($expression === null) {
            throw new \InvalidArgumentException('Required sub-extension "expression" not found or type mismatch in ' . static::class . '::fromSubExtensions()');
        }

        return new static($option, $expression, $id);
    }
}
