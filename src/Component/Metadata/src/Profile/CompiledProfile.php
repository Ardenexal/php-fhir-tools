<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Profile;

/**
 * The attributes one StructureDefinition compiles to, plus any rules that could not be compiled.
 *
 * @author Ardenexal
 */
final readonly class CompiledProfile
{
    /**
     * @param string                   $profileUrl  Canonical URL of the StructureDefinition; used as the validation group
     * @param list<CompiledAttribute>  $attributes  All compiled attributes, in emission order
     * @param list<CompilerDiagnostic> $diagnostics Rules found but not compiled
     */
    public function __construct(
        public string $profileUrl,
        public array $attributes,
        public array $diagnostics = [],
    ) {
    }

    /**
     * The compiled attributes that are Symfony Validator constraints.
     *
     * @return list<CompiledAttribute>
     */
    public function constraints(): array
    {
        return array_values(array_filter($this->attributes, static fn (CompiledAttribute $a): bool => $a->isConstraint()));
    }

    /**
     * The compiled attributes that are metadata read by other code, not validated directly.
     *
     * @return list<CompiledAttribute>
     */
    public function metadata(): array
    {
        return array_values(array_filter($this->attributes, static fn (CompiledAttribute $a): bool => !$a->isConstraint()));
    }
}
