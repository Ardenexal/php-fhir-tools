<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Profile;

use Symfony\Component\Validator\Constraint;

/**
 * One attribute a StructureDefinition compiles to: the attribute class and its named arguments.
 *
 * The code generator renders it as a PHP attribute on a profile class; runtime validation
 * instantiates it. Arguments hold only scalars and arrays, so a compiled profile can be cached.
 *
 * @author Ardenexal
 */
final readonly class CompiledAttribute
{
    /**
     * @param class-string         $class     Attribute class, e.g. FHIRProfileConstraint::class
     * @param array<string, mixed> $arguments Named constructor arguments, in the order they are rendered
     */
    public function __construct(
        public string $class,
        public array $arguments,
    ) {
    }

    /**
     * Whether the attribute is a Symfony Validator constraint, as opposed to pure metadata such as
     * FHIRSlicingRules or FHIRProfileMustSupport that other code reads.
     */
    public function isConstraint(): bool
    {
        return is_subclass_of($this->class, Constraint::class);
    }

    /**
     * Builds the attribute instance from its class and arguments.
     */
    public function instantiate(): object
    {
        return new ($this->class)(...$this->arguments);
    }
}
