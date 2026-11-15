<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Profile\StructureDefinitionCompiler;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Renders a profile's compiled validation rules as attributes on its generated class.
 *
 * Both profile generators use it, so a resource profile class and a data-type profile class carry
 * exactly what StructureDefinitionCompiler produces, and runtime validation, which compiles with the
 * same compiler, enforces the same rules.
 *
 * @author Ardenexal
 */
final class CompiledProfileAttributeEmitter
{
    public function __construct(
        private readonly StructureDefinitionCompiler $compiler = new StructureDefinitionCompiler(),
    ) {
    }

    /**
     * Compile the profile and add each rule to the class, forwarding diagnostics as warnings.
     *
     * Data-type profiles the profile names on its elements are looked up among the definitions being
     * generated, the way runtime validation looks them up in its registry.
     *
     * @param array<string, mixed> $structureDefinition StructureDefinition with derivation=constraint
     */
    public function emit(
        array $structureDefinition,
        ClassType $class,
        PhpNamespace $namespace,
        BuilderContext $context,
        ?ErrorCollector $errorCollector,
    ): void {
        $compiled = $this->compiler->compile(
            $structureDefinition,
            static fn (string $url): ?array => $context->getDefinition($url),
        );

        foreach ($compiled->diagnostics as $diagnostic) {
            $errorCollector?->addWarning($diagnostic->message, $diagnostic->elementId, $diagnostic->context);
        }

        foreach ($compiled->attributes as $attribute) {
            $namespace->addUse($attribute->class);

            // A profile constraint names its inner rule by class string; import it alongside
            if ($attribute->class === FHIRProfileConstraint::class && is_string($attribute->arguments['constraint'] ?? null)) {
                $namespace->addUse($attribute->arguments['constraint']);
            }

            $class->addAttribute($attribute->class, $attribute->arguments);
        }
    }
}
