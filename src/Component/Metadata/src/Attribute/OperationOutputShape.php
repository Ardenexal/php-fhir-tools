<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute;

/**
 * How an operation's response is shaped on the wire.
 *
 * "The wire format of an operation response IS a `Parameters` resource" is true for only about a
 * quarter of operations. Measured across the shipped core packages:
 *
 * | Shape             | R4       | R4B      | R5       |
 * |-------------------|----------|----------|----------|
 * | Parameters        | 14 (30%) | 14 (30%) | 16 (26%) |
 * | BareResource      | 27 (57%) | 27 (57%) | 39 (64%) |
 * | NamedBareResource | 3        | 3        | 3        |
 * | NoOutput          | 3        | 3        | 2        |
 *
 * The shape is decided at generation time and carried on the operation holder, because a mapper
 * that assumes `Parameters` produces wrong output for the majority case.
 *
 * @author Ardenexal
 */
enum OperationOutputShape: string
{
    /**
     * Multiple OUT parameters, or a single primitive one: a genuine `Parameters` resource.
     *
     * `CodeSystem/$lookup` is this shape — it returns `name`, `display`, `designation[]` and
     * `property[]` side by side, which nothing but `Parameters` can carry.
     */
    case Parameters = 'parameters';

    /**
     * A sole OUT parameter named `return` holding a resource: the response IS that resource.
     *
     * `Patient/$everything` returns a `Bundle`, not a `Parameters` wrapping one. The majority shape.
     */
    case BareResource = 'bare-resource';

    /**
     * A sole resource-typed OUT parameter under some name other than `return` — **wrapped**.
     *
     * The specification's un-wrap rule is conditioned on the *name*: "If there is only one out
     * parameter, which is a Resource with the parameter name **"return"** then the parameter format
     * is not used". A sole resource OUT under any other name fails that condition, so the parameter
     * format *is* used and the resource arrives inside a one-parameter `Parameters`.
     *
     * `Resource/$graph` is this shape — a `Bundle` named `result`. Only 3 operations per version
     * qualify, but collapsing them into BareResource is wrong in both directions: it reads a wrapped
     * body as bare, and emits a bare body a server would have to guess at. The name is carried on
     * {@see FhirOperation::$outputParameterName} because it cannot be assumed.
     *
     * (Despite the case name, this shape is NOT bare. It is named for the *parameter*, which is a
     * bare resource sitting in the wrapper's `resource` slot.)
     */
    case NamedBareResource = 'named-bare-resource';

    /**
     * No OUT parameters at all — a successful invocation yields no body.
     *
     * Modelled explicitly so "operation succeeded with no output" stays distinguishable from
     * "response failed to parse".
     */
    case NoOutput = 'no-output';
}
