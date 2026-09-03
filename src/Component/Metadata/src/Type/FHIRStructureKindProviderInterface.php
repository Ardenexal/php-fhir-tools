<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;

/**
 * Answers what kind of FHIR structure a class is, with the inheritance question made explicit.
 *
 * PHP does not inherit class-level attributes. `ReflectionClass::getAttributes()` returns only what a
 * class declares itself, so a profile subclass of a resource reports no `#[FhirResource]` of its own
 * even though it plainly is one. Both readings are legitimate and callers in this codebase rely on
 * each, which is why they are separate methods here rather than one method with a guessed default:
 *
 *  - {@see declaredKindOf()} answers what the class itself is marked as. Use it where a subclass must
 *    NOT inherit the answer -- for instance deciding whether a value is a backbone element, where a
 *    profile of a backbone element is a different structural case.
 *  - {@see inheritedKindOf()} walks the parent chain. Use it where a profile should be treated as the
 *    thing it profiles.
 *
 * Picking the wrong one changes behaviour silently and only for profiled types, which ordinary
 * fixtures do not cover. When migrating an existing `getAttributes()` call, match what it does today:
 * a bare `getAttributes()` read is `declaredKindOf()`, and a `do { ... } while (getParentClass())`
 * walk is `inheritedKindOf()`.
 *
 * @author Ardenexal
 */
interface FHIRStructureKindProviderInterface
{
    /**
     * The structure kind this class declares itself, ignoring anything its ancestors declare.
     *
     * @param object|string $subject An instance, or a class name; a string naming no loadable class answers null
     *
     * @return FHIRStructureKind|null The declared kind, or null when the class carries no structural
     *                                attribute of its own -- including a profile subclass whose base
     *                                carries one
     */
    public function declaredKindOf(object|string $subject): ?FHIRStructureKind;

    /**
     * The structure kind this class or its nearest marked ancestor declares.
     *
     * @param object|string $subject An instance, or a class name; a string naming no loadable class answers null
     *
     * @return FHIRStructureKind|null The nearest kind up the chain, or null when neither the class nor
     *                                any ancestor is marked
     */
    public function inheritedKindOf(object|string $subject): ?FHIRStructureKind;

    /**
     * The nearest kind up the chain that is one of `$kinds`, ignoring ancestors of any other kind.
     *
     * Distinct from {@see inheritedKindOf()}, which stops at the first marked ancestor whatever its
     * kind. Some callers walk past kinds they do not care about -- asking "is this a complex type or a
     * primitive" while treating a backbone-element ancestor as neither and continuing upward. Using
     * `inheritedKindOf()` there would stop the walk early and change the answer.
     *
     * @param object|string     $subject  An instance, or a class name; a string naming no loadable class answers null
     * @param FHIRStructureKind ...$kinds Kinds the caller is willing to stop on
     *
     * @return FHIRStructureKind|null The first listed kind found walking upward, or null when no
     *                                ancestor declares any of them
     */
    public function nearestKindAmong(object|string $subject, FHIRStructureKind ...$kinds): ?FHIRStructureKind;

    /**
     * The FHIR type name a class declares on its own structural attribute.
     *
     * The published FHIR name -- `HumanName`, `Patient`, `code`, `Substance.ingredient` -- which is
     * what a conformance message should say rather than a PHP class name, whose suffixes (`Resource`,
     * `Primitive`, `Profile`) and flattened dots (`DosageDoseAndRate`) do not exist in the spec.
     *
     * Each structural attribute spells the argument differently -- `typeName`, `primitiveType`,
     * `elementPath`, `name`, `baseType`, and `type` on `FhirResource` alone -- so implementations MUST
     * read the specific attribute rather than scanning arguments for a shared name. A scan looks
     * general and answers null for everything but a resource.
     *
     * A profile answers as the type it constrains, from `#[FHIRProfile(baseType:)]`: a message naming
     * `ActualGroupProfile` sends the reader looking for a spec type that does not exist, where `Group`
     * is the type they can actually look up.
     *
     * Read from the class's own attributes only. A subclass that declares no structural attribute of
     * its own answers null, and the caller falls back to the class name.
     *
     * @param object|string $subject An instance or class name; an unloadable name gives null
     *
     * @return string|null The declared FHIR type name, or null when the class declares none
     */
    public function declaredFhirTypeName(object|string $subject): ?string;

    /**
     * The nearest `#[FHIRPrimitive]` up the class chain, as an instance rather than a kind.
     *
     * `inheritedKindOf()` answers *whether* a class is a primitive and discards the attribute that
     * said so. Callers that need the attribute's own fields -- the FHIR type name it records, say --
     * have to read it, and doing that by hand is a reflection walk in the caller. This returns it.
     *
     * @param object|string $subject An instance or class name; an unloadable name gives null
     *
     * @return FHIRPrimitive|null The attribute from the nearest class carrying one, or null
     */
    public function nearestPrimitiveAttribute(object|string $subject): ?FHIRPrimitive;

    /**
     * Whether the class is marked as defining a FHIR extension.
     *
     * Separate from structure kind because the two are orthogonal: an extension definition is also a
     * complex type, so folding it into the kind enum would force a choice between two true answers.
     *
     * @param object|string $subject An instance, or a class name; a string naming no loadable class answers null
     *
     * @return bool True when the class itself declares `#[FHIRExtensionDefinition]`
     */
    public function isExtensionDefinition(object|string $subject): bool;
}
